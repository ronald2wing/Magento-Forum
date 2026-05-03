<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Topic;

use Ronald2Wing\Forum\Api\Data\PostInterface;
use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Helper\Data as ForumData;
use Ronald2Wing\Forum\Helper\Url as ForumUrl;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Ronald2Wing\Forum\Model\ForumFactory;
use Ronald2Wing\Forum\Model\PostFactory;
use Ronald2Wing\Forum\Model\Service\AuthorisationService;
use Ronald2Wing\Forum\Model\TopicFactory;
use Ronald2Wing\Forum\Model\Service\UrlKeyGenerator;
use Ronald2Wing\Forum\Model\Service\NotificationService;

class Save implements ActionInterface, HttpPostActionInterface
{
    private bool $flagTopicIsNew = false;
    private bool $flagPostIsNew = false;
    private array $postData = [];
    private string $postPreview = '';

    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly RequestInterface $request,
        private readonly CustomerSession $customerSession,
        private readonly PostFactory $postFactory,
        private readonly TopicFactory $topicFactory,
        private readonly ForumFactory $forumFactory,
        private readonly NotificationService $notificationService,
        private readonly PostDataProcessor $postDataProcessor,
        private readonly UrlKeyGenerator $urlKeyGenerator,
        private readonly ForumData $forumData,
        private readonly DateTime $date,
        private readonly AuthorisationService $authService,
        private readonly ForumUrl $helperUrl,
        private readonly FormKeyValidator $formKeyValidator,
        private readonly ManagerInterface $messageManager
    ) {}

    public function execute(): ResultInterface
    {
        if (!$this->customerSession->isLoggedIn()) {
            $this->customerSession->setAfterAuthUrl(
                $this->helperUrl->getForumUrl()
            );
            $this->customerSession->authenticate();
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        if (!$this->formKeyValidator->validate($this->request)) {
            $this->messageManager->addError(__('Invalid form key. Please refresh the page.'));
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        if (!$this->validate()) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        $post = $this->request->getPostValue();
        $this->postData = $this->postDataProcessor->filter(is_array($post) ? $post : []);

        $flagTopicSaved = false;
        try {
            $topicModel = $this->getTopicModel();

            if (!$topicModel || !$topicModel->getId() || $this->authService->canModify($topicModel)) {
                $topicId = $this->saveTopic();
                $flagTopicSaved = true;
            } else {
                $topicId = (int) $topicModel->getId();
            }

            if (!$topicId) {
                return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
            }

            $this->savePost($topicId);

            if ($flagTopicSaved) {
                $this->messageManager->addSuccess($this->getTopicSavedMessage());
            }
            $this->messageManager->addSuccess($this->getPostSavedMessage());
        } catch (\Exception $e) {
            if (isset($topicId) && $topicId && $this->flagTopicIsNew) {
                $orphanedTopic = $this->topicFactory->create();
                $orphanedTopic->load($topicId);
                if ($orphanedTopic->getId()) {
                    $orphanedTopic->setIsDeleted(true);
                    $orphanedTopic->save();
                }
            }
            $this->messageManager->addError($e->getMessage());
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)
            ->setUrl($this->getRedirectUrl());
    }

    private function getTopicModel(): ?\Ronald2Wing\Forum\Model\Topic
    {
        $topicId = (int) $this->request->getParam(Constant::PARAM_TOPIC_ID);
        if (!$topicId) {
            return null;
        }
        $model = $this->topicFactory->create();
        $model->load($topicId);
        return $model;
    }

    private function validate(): bool
    {
        $forumId = (int) $this->request->getParam(Constant::PARAM_FORUM_ID);
        if (!$forumId) {
            return false;
        }

        $forumModel = $this->forumFactory->create();
        $forumModel->load($forumId);

        if (!$forumModel->getId() || $forumModel->getIsDeleted() || !$forumModel->getStatus()) {
            return false;
        }

        $topicId = (int) $this->request->getParam(Constant::PARAM_TOPIC_ID);
        if ($topicId) {
            $topicModel = $this->topicFactory->create();
            $topicModel->load($topicId);
            if (!$topicModel->getId()
                || $topicModel->getIsDeleted()
                || !$topicModel->getStatus()
            ) {
                return false;
            }
        }

        $postId = (int) $this->request->getParam(Constant::PARAM_POST_ID);
        if ($postId) {
            $postModel = $this->postFactory->create();
            $postModel->load($postId);
            if (!$postModel->getId()
                || $postModel->getIsDeleted()
                || !$postModel->getStatus()
                || !$this->authService->canModify($postModel)
            ) {
                return false;
            }
        }

        return true;
    }

    private function saveTopic(): int
    {
        $forumModel = $this->forumFactory->create();
        $forumModel->load((int) $this->request->getParam(Constant::PARAM_FORUM_ID));

        if ($this->request->getParam('post_only')) {
            $topicModel = $this->getTopicModel();
            return $topicModel ? (int) $topicModel->getId() : 0;
        }

        $topicModel = $this->getTopicModel();
        if (!$topicModel || !$topicModel->getId()) {
            $topicModel = $this->topicFactory->create();
            $this->flagTopicIsNew = true;
            $urlKey = $this->urlKeyGenerator->buildUniqueTopicUrlKey(
                (string) ($this->postData['title'] ?? ''),
                (int) $forumModel->getId()
            );
            $topicModel->setUrlKey($urlKey);
            $topicModel->setCreatedAt($this->date->gmtDate());
        } else {
            $topicModel->setUpdatedAt($this->date->gmtDate());
        }

        if (!empty($this->postData['icon_id']) && $this->postData['icon_id'][0] !== '') {
            $topicModel->setIconId($this->postData['icon_id'][0]);
        } else {
            $topicModel->setIconId('');
        }

        $topicModel->setTitle((string) ($this->postData['title'] ?? ''));
        $topicModel->setDescription((string) ($this->postData['description'] ?? ''));
        $topicModel->setStatus($this->getStatus());
        $topicModel->setForumId((int) $forumModel->getId());

        if (!(int) $topicModel->getUserId()) {
            $topicModel->setUserId((int) $this->customerSession->getId());
        }

        $topicModel->save();
        return (int) $topicModel->getId();
    }

    private function savePost(int $topicId): void
    {
        $postText = (string) ($this->postData['post'] ?? '');
        $postOrig = strip_tags($postText);
        $this->postPreview = mb_substr($postOrig, 0, 200);

        $postId = (int) $this->request->getParam(Constant::PARAM_POST_ID);
        $postModel = $this->postFactory->create();

        if ($postId) {
            $postModel->load($postId);
        }

        if (!$postModel->getId()) {
            $this->flagPostIsNew = true;
            $postModel->setCreatedAt($this->date->gmtDate());
        } else {
            $postModel->setUpdatedAt($this->date->gmtDate());
        }

        if (!(int) $postModel->getUserId()) {
            $postModel->setUserId((int) $this->customerSession->getId());
        }

        $forumModel = $this->forumFactory->create();
        $forumModel->load((int) $this->request->getParam(Constant::PARAM_FORUM_ID));

        $postModel->setStatus($this->getStatus());
        $postModel->setTopicId($topicId);
        $postModel->setForumId((int) $forumModel->getId());
        $postModel->setContent($postText);
        $postModel->setContentOriginal($postOrig);
        $postModel->save();

        if ($this->forumData->isNotifyCustomerEnabled()) {
            $this->saveNotification($topicId);
            $this->sendNotifications($topicId);
        }
    }

    private function saveNotification(int $topicId): void
    {
        if (empty($this->postData['notify_my'])) {
            return;
        }
        $this->notificationService->subscribe((int) $this->customerSession->getId(), $topicId);
    }

    private function sendNotifications(int $topicId): void
    {
        $topicModel = $this->topicFactory->create();
        $topicModel->load($topicId);

        $forum = $this->forumFactory->create();
        $forum->load((int) $topicModel->getForumId());

        $this->notificationService->sendNotification(
            $topicId,
            (string) $topicModel->getTitle(),
            $this->helperUrl->getTopicUrl(
                (string) $forum->getUrlKey(),
                (string) $topicModel->getUrlKey()
            ),
            $this->postPreview
        );
    }

    private function getStatus(): int
    {
        if ($this->authService->isModerator()) {
            return PostInterface::STATUS_ENABLED;
        }
        return PostInterface::STATUS_PENDING;
    }

    private function getRedirectUrl(): string
    {
        return $this->helperUrl->getForumUrl();
    }

    private function getTopicSavedMessage(): \Magento\Framework\Phrase
    {
        if ($this->flagTopicIsNew) {
            if ($this->getStatus() === PostInterface::STATUS_PENDING) {
                return __('Topic successfully saved and will be added after moderation!');
            }
            return __('Topic successfully saved!');
        }
        if ($this->getStatus() === PostInterface::STATUS_PENDING) {
            return __('Topic successfully updated and will be added after moderation!');
        }
        return __('Topic successfully updated!');
    }

    private function getPostSavedMessage(): \Magento\Framework\Phrase
    {
        if ($this->flagPostIsNew) {
            if ($this->getStatus() === PostInterface::STATUS_PENDING) {
                return __('Post successfully saved and will be added after moderation!');
            }
            return __('Post successfully saved!');
        }
        if ($this->getStatus() === PostInterface::STATUS_PENDING) {
            return __('Post successfully updated and will be added after moderation!');
        }
        return __('Post successfully updated!');
    }
}
