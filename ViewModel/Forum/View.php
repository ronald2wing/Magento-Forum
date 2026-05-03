<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Forum;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;
use Ronald2Wing\Forum\Helper\Data as ForumData;
use Ronald2Wing\Forum\Helper\Url as UrlHelper;
use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Api\ForumRepositoryInterface;
use Ronald2Wing\Forum\Api\Data\ForumInterface;
use Ronald2Wing\Forum\Model\Icon;
use Ronald2Wing\Forum\Model\ResourceModel\Topic\Collection as TopicCollection;
use Ronald2Wing\Forum\Model\ResourceModel\Topic\CollectionFactory as TopicCollectionFactory;
use Ronald2Wing\Forum\Model\Service\AuthorisationService;
use Ronald2Wing\Forum\Model\Service\UserProfileService;
use Magento\Framework\Exception\NoSuchEntityException;

class View implements ArgumentInterface
{
    private ?ForumInterface $parentForum = null;

    public function __construct(
        private readonly RequestInterface $request,
        private readonly ForumData $forumData,
        private readonly UrlHelper $urlHelper,
        private readonly Icon $icon,
        private readonly TopicCollectionFactory $topicCollectionFactory,
        private readonly UserProfileService $forumUser,
        private readonly Latest $latestModel,
        private readonly ForumRepositoryInterface $forumRepository,
        private readonly AuthorisationService $authService,
        private readonly CustomerSession $customerSession,
        private readonly StoreManagerInterface $storeManager
    ) {}

    public function getIsModerator(): bool
    {
        return $this->authService->isModerator();
    }

    public function getAllTopics(): TopicCollection
    {
        $forumId = $this->getForumId();
        if (!$forumId) {
            return $this->topicCollectionFactory->create()->setPageSize(0);
        }
        return $this->topicCollectionFactory->create()
            ->byForum($forumId)
            ->enabledOnly()
            ->setOrder($this->getSortField(), $this->getSort())
            ->setCurPage($this->getPageNum());
    }

    public function formatDateTime(string $dateTime): string
    {
        return $this->forumData->formatDateTime($dateTime);
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

    public function getForumId(): ?int
    {
        return $this->request->getParam(Constant::PARAM_FORUM_ID)
            ? (int) $this->request->getParam(Constant::PARAM_FORUM_ID)
            : null;
    }

    public function getSort(): string
    {
        return $this->request->getParam(Constant::PARAM_SORT)
            ?? Constant::SORT_CREATED_DESC;
    }

    public function getSortField(): string
    {
        $type = $this->getSort();
        if (in_array($type, [Constant::SORT_TITLE_ASC, Constant::SORT_TITLE_DESC], true)) {
            return 'title';
        }
        if (in_array($type, [Constant::SORT_POSTS_ASC, Constant::SORT_POSTS_DESC], true)) {
            return 'total_posts';
        }
        return 'created_at';
    }

    public function getPageNum(): int
    {
        return (int) $this->request->getParam(Constant::PARAM_PAGE, 1);
    }

    public function getLimit(): int
    {
        return (int) $this->request->getParam(Constant::PARAM_LIMIT, Constant::DEFAULT_PAGE_SIZE_TOPIC);
    }

    public function getUserName(int $userId): string
    {
        return $this->forumUser->getCustomerName($userId);
    }

    public function getIconSrc(?ForumInterface $model = null): string
    {
        $iconId = $model ? $model->getIconId() : ($this->getParentForum()?->getIconId());
        if (!$iconId) {
            return '';
        }
        return $this->icon->getIconUrl($iconId, $this->storeManager);
    }

    public function getLatestPostDetails(int $postId): ?\Ronald2Wing\Forum\Api\Data\PostInterface
    {
        return $this->latestModel->getLatestPostData($postId);
    }
}
