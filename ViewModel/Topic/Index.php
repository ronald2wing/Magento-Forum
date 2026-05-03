<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Topic;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;
use Ronald2Wing\Forum\Helper\Data as ForumData;
use Ronald2Wing\Forum\Helper\Url as UrlHelper;
use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Api\ForumRepositoryInterface;
use Ronald2Wing\Forum\Api\TopicRepositoryInterface;
use Ronald2Wing\Forum\Api\Data\ForumInterface;
use Ronald2Wing\Forum\Api\Data\TopicInterface;
use Ronald2Wing\Forum\Model\Icon;
use Ronald2Wing\Forum\Model\ResourceModel\Post\Collection as PostCollection;
use Ronald2Wing\Forum\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use Ronald2Wing\Forum\Model\Service\AuthorisationService;
use Ronald2Wing\Forum\Model\Service\UserProfileService;
use Magento\Framework\Exception\NoSuchEntityException;

class Index implements ArgumentInterface
{
    private ?ForumInterface $parentForum = null;
    private ?TopicInterface $parentTopic = null;

    public function __construct(
        private readonly RequestInterface $request,
        private readonly ForumData $forumData,
        private readonly UrlHelper $urlHelper,
        private readonly Icon $icon,
        private readonly PostCollectionFactory $postCollectionFactory,
        private readonly UserProfileService $forumUser,
        private readonly ForumRepositoryInterface $forumRepository,
        private readonly TopicRepositoryInterface $topicRepository,
        private readonly AuthorisationService $authService,
        private readonly CustomerSession $customerSession,
        private readonly StoreManagerInterface $storeManager
    ) {}

    public function getAllPosts(): PostCollection
    {
        $topicId = $this->getTopicId();
        $collection = $this->postCollectionFactory->create();
        if (!$topicId) {
            return $collection->setPageSize(0);
        }
        return $collection
            ->addFieldToFilter('topic_id', $topicId)
            ->enabledOnly()
            ->notDeleted()
            ->setOrder($this->getSortField(), $this->getSort())
            ->setCurPage($this->getPageNum());
    }

    public function getSort(): string
    {
        return $this->request->getParam(Constant::PARAM_SORT)
            ?? Constant::SORT_CREATED_DESC;
    }

    public function getIsModerator(): bool
    {
        return $this->authService->isModerator();
    }

    public function getSortField(): string
    {
        return 'created_at';
    }

    public function getPageNum(): int
    {
        return (int) $this->request->getParam(Constant::PARAM_PAGE, 1);
    }

    public function getLimit(): int
    {
        return (int) $this->request->getParam(Constant::PARAM_LIMIT, Constant::DEFAULT_PAGE_SIZE_POST);
    }

    public function getParentForum(): ?ForumInterface
    {
        if ($this->parentForum === null) {
            $forumId = $this->getForumId();
            if ($forumId) {
                try {
                    $this->parentForum = $this->forumRepository->getById($forumId);
                } catch (NoSuchEntityException) {
                    $this->parentForum = null;
                }
            }
        }
        return $this->parentForum;
    }

    public function getParentTopic(): ?TopicInterface
    {
        if ($this->parentTopic === null) {
            $topicId = $this->getTopicId();
            if ($topicId) {
                try {
                    $this->parentTopic = $this->topicRepository->getById($topicId);
                } catch (NoSuchEntityException) {
                    $this->parentTopic = null;
                }
            }
        }
        return $this->parentTopic;
    }

    public function getTopicId(): ?int
    {
        return $this->request->getParam(Constant::PARAM_TOPIC_ID)
            ? (int) $this->request->getParam(Constant::PARAM_TOPIC_ID)
            : null;
    }

    public function getForumId(): ?int
    {
        return $this->request->getParam(Constant::PARAM_FORUM_ID)
            ? (int) $this->request->getParam(Constant::PARAM_FORUM_ID)
            : null;
    }

    public function getIsLoggedIn(): bool
    {
        return $this->customerSession->isLoggedIn();
    }

    public function formatDateTime(string $dateTime): string
    {
        return $this->forumData->formatDateTime($dateTime);
    }

    public function getCustomerSession(): CustomerSession
    {
        return $this->customerSession;
    }

    public function getIsOwner(TopicInterface $object): bool
    {
        return $this->authService->canModify($object);
    }

    public function getIconSrc(?TopicInterface $model = null): string
    {
        $entity = $model ?: $this->getParentTopic();
        if (!$entity || !$entity->getIconId()) {
            return '';
        }
        return $this->icon->getIconUrl($entity->getIconId(), $this->storeManager);
    }
}
