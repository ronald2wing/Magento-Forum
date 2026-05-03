<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Topic;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Ronald2Wing\Forum\Helper\Data as ForumData;
use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Api\ForumRepositoryInterface;
use Ronald2Wing\Forum\Api\TopicRepositoryInterface;
use Ronald2Wing\Forum\Api\Data\ForumInterface;
use Ronald2Wing\Forum\Api\Data\TopicInterface;
use Ronald2Wing\Forum\Api\Data\PostInterface;
use Ronald2Wing\Forum\Model\PostFactory;
use Ronald2Wing\Forum\Model\Service\AuthorisationService;
use Magento\Framework\Exception\NoSuchEntityException;

class Edit implements ArgumentInterface
{
    private ?ForumInterface $editForum = null;
    private ?TopicInterface $editTopic = null;
    private ?PostInterface $editPost = null;

    public function __construct(
        private readonly RequestInterface $request,
        private readonly ForumData $forumData,
        private readonly ForumRepositoryInterface $forumRepository,
        private readonly TopicRepositoryInterface $topicRepository,
        private readonly AuthorisationService $authService,
        private readonly PostFactory $postFactory,
        private readonly CustomerSession $customerSession
    ) {}

    public function getIsModerator(): bool
    {
        return $this->authService->isModerator();
    }

    public function getFormTitle(): string
    {
        $topic = $this->getEditTopic();
        if (!$topic) {
            return (string) __('Add New Topic');
        }
        return (string) __('Edit Topic');
    }

    public function getEditForum(): ?ForumInterface
    {
        if ($this->editForum === null) {
            $forumId = $this->getForumId();
            if ($forumId) {
                try {
                    $this->editForum = $this->forumRepository->getById($forumId);
                } catch (NoSuchEntityException) {
                    $this->editForum = null;
                }
            }
        }
        return $this->editForum;
    }

    public function getEditTopic(): ?TopicInterface
    {
        if ($this->editTopic === null) {
            $topicId = $this->getTopicId();
            if ($topicId) {
                try {
                    $this->editTopic = $this->topicRepository->getById($topicId);
                } catch (NoSuchEntityException) {
                    $this->editTopic = null;
                }
            }
        }
        return $this->editTopic;
    }

    public function getEditPost(): ?PostInterface
    {
        if ($this->editPost === null) {
            $postId = $this->getPostId();
            if ($postId) {
                $post = $this->postFactory->create();
                $post->load($postId);
                if ($post->getId()) {
                    $this->editPost = $post;
                }
            }
        }
        return $this->editPost;
    }

    public function getCurrentCustomerSession(): CustomerSession
    {
        return $this->customerSession;
    }

    public function getIsOwner(TopicInterface $object): bool
    {
        return $this->authService->canModify($object);
    }

    public function getForumId(): ?int
    {
        return $this->request->getParam(Constant::PARAM_FORUM_ID)
            ? (int) $this->request->getParam(Constant::PARAM_FORUM_ID)
            : null;
    }

    public function getTopicId(): ?int
    {
        return $this->request->getParam(Constant::PARAM_TOPIC_ID)
            ? (int) $this->request->getParam(Constant::PARAM_TOPIC_ID)
            : null;
    }

    public function getPostId(): ?int
    {
        return $this->request->getParam(Constant::PARAM_POST_ID)
            ? (int) $this->request->getParam(Constant::PARAM_POST_ID)
            : null;
    }
}
