<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Topic;

use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Helper\Url as ForumUrl;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Ronald2Wing\Forum\Model\ForumFactory;
use Ronald2Wing\Forum\Model\PostFactory;
use Ronald2Wing\Forum\Model\Service\AuthorisationService;
use Ronald2Wing\Forum\Model\TopicFactory;

class Edit implements ActionInterface, HttpGetActionInterface
{
    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly PageFactory $resultPageFactory,
        private readonly RequestInterface $request,
        private readonly Registry $registry,
        private readonly CustomerSession $customerSession,
        private readonly ForumFactory $forumFactory,
        private readonly TopicFactory $topicFactory,
        private readonly PostFactory $postFactory,
        private readonly StoreManagerInterface $storeManager,
        private readonly AuthorisationService $authService,
        private readonly ForumUrl $forumUrl,
        private readonly ManagerInterface $messageManager
    ) {}

    public function execute(): ResultInterface
    {
        if (!$this->customerSession->isLoggedIn()) {
            $this->customerSession->setAfterAuthUrl(
                $this->forumUrl->getForumUrl()
            );
            $this->customerSession->authenticate();
        }

        $forumId = (int) $this->request->getParam(Constant::PARAM_FORUM_ID);
        $topicId = (int) $this->request->getParam(Constant::PARAM_TOPIC_ID);
        $postId = (int) $this->request->getParam(Constant::PARAM_POST_ID);

        $forumModel = $this->forumFactory->create();
        $forumModel->load($forumId);

        $this->registry->register(Constant::REGISTRY_CUSTOMER_SESSION, $this->customerSession);

        if (!$forumModel->getIsDeleted() && $forumModel->getStatus()) {
            $this->registry->register(Constant::EDIT_OBJECT_FORUM, $forumModel);
        }

        if (!$forumModel->getId()) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        $topicModel = null;
        if ($topicId) {
            $topicModel = $this->topicFactory->create();
            $topicModel->load($topicId);
            if ($topicModel->getId()
                && $topicModel->getStatus()
                && !$topicModel->getIsDeleted()
                && (int) $topicModel->getForumId() === (int) $forumModel->getId()
            ) {
                $this->registry->register(Constant::EDIT_OBJECT_TOPIC, $topicModel);
            }
        }
        if (!$this->registry->registry(Constant::EDIT_OBJECT_TOPIC) && $topicId) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        if ($postId) {
            $postModel = $this->postFactory->create();
            $postModel->load($postId);
            if ($postModel->getId()
                && $postModel->getStatus()
                && !$postModel->getIsDeleted()
                && (int) ($postModel->getForumId()) === (int) $forumModel->getId()
                && ((int) $this->customerSession->getId() === (int) $postModel->getUserId()
                    || $this->authService->isModerator())
            ) {
                $this->registry->register(Constant::EDIT_OBJECT_POST, $postModel);
            }
        }
        if (!$this->registry->registry(Constant::EDIT_OBJECT_POST) && $postId) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        if (!$this->authService->isAllowed($forumModel)) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)
                ->setUrl($this->forumUrl->getForumUrl());
        }

        $resultPage = $this->resultPageFactory->create();
        $breadcrumbs = $resultPage->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs) {
            $breadcrumbs->addCrumb('forum_home', [
                'label' => __('Forum'),
                'title' => __('Forum'),
                'link' => '/' . $this->forumUrl->getForumUrl(),
            ]);

            if ($forumModel->getId()) {
                $breadcrumbs->addCrumb('forum_view', [
                    'label' => $forumModel->getTitle(),
                    'title' => $forumModel->getTitle(),
                    'link' => $this->forumUrl->getTopicUrl(
                        (string) $forumModel->getUrlKey(),
                        (string) $topicModel?->getUrlKey()
                    ),
                ]);
            }

            if ($topicModel && $topicModel->getId()) {
                $breadcrumbs->addCrumb('forum_topic', [
                    'label' => __('Topic: %1', $topicModel->getTitle()),
                    'title' => __('Topic: %1', $topicModel->getTitle()),
                    'link' => $this->forumUrl->getTopicUrl(
                        (string) $forumModel->getUrlKey(),
                        (string) $topicModel->getUrlKey()
                    ),
                ]);

                if ($this->registry->registry(Constant::EDIT_OBJECT_POST)) {
                    $breadcrumbs->addCrumb('forum_post', [
                        'label' => __('Edit Post'),
                        'title' => __('Edit Post'),
                    ]);
                } else {
                    $breadcrumbs->addCrumb('forum_new_post', [
                        'label' => __('Add New Post'),
                        'title' => __('Add New Post'),
                    ]);
                }
            } else {
                $breadcrumbs->addCrumb('forum_topic', [
                    'label' => __('Add New Topic'),
                    'title' => __('Add New Topic'),
                ]);
            }
        }

        return $resultPage;
    }
}
