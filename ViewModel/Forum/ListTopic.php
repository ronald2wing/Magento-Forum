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
use Ronald2Wing\Forum\Api\Data\TopicInterface;
use Ronald2Wing\Forum\Model\Icon;
use Ronald2Wing\Forum\Model\ResourceModel\Topic\Collection as TopicCollection;
use Ronald2Wing\Forum\Model\Service\AuthorisationService;
use Ronald2Wing\Forum\Model\Service\UserProfileService;
use Magento\Framework\Exception\NoSuchEntityException;

class ListTopic implements ArgumentInterface
{
    private ?ForumInterface $parentForum = null;

    public function __construct(
        private readonly RequestInterface $request,
        private readonly ForumData $forumData,
        private readonly UrlHelper $urlHelper,
        private readonly Icon $icon,
        private readonly UserProfileService $forumUser,
        private readonly Latest $latestModel,
        private readonly ForumRepositoryInterface $forumRepository,
        private readonly AuthorisationService $authService,
        private readonly CustomerSession $customerSession,
        private readonly StoreManagerInterface $storeManager
    ) {}

    public function getIsIconsEnabled(): bool
    {
        return $this->forumData->isIconsAllowed();
    }

    public function getIconSrc(?ForumInterface $model = null): string
    {
        $entity = $model ?: $this->getParentForum();
        if (!$entity || !$entity->getIconId()) {
            return '';
        }
        return $this->icon->getIconUrl($entity->getIconId(), $this->storeManager);
    }

    public function getTopicViewUrl(ForumInterface $forumObj, TopicInterface $topicObj): string
    {
        return $this->urlHelper->getTopicUrl(
            (string) $forumObj->getUrlKey(),
            (string) $topicObj->getUrlKey()
        );
    }

    public function getTitle(): string
    {
        return $this->getParentForum()?->getTitle() ?? '';
    }

    public function getDisplayTopics(TopicCollection $collection, int $pageLimit, int $pageNum): TopicCollection
    {
        $collection->setPageSize($pageLimit);
        $collection->setCurPage($pageNum);
        return $collection;
    }

    public function getSortUrl(string $type = 'asc'): string
    {
        return $this->urlHelper->getForumUrl();
    }

    public function getTopicCreatorName(TopicInterface $topicModel): string
    {
        $userId = $topicModel->getUserId();
        return $userId ? $this->forumUser->getCustomerName($userId) : '';
    }

    public function getAddNewUrl(): string
    {
        $forumId = $this->getForumId();
        return $forumId ? $this->urlHelper->getAddTopicUrl($forumId) : '';
    }

    public function getIsOwner(TopicInterface $topicModel): bool
    {
        return $this->authService->canModify($topicModel);
    }

    public function getEditLink(TopicInterface $topicModel): string
    {
        return $this->urlHelper->getEditTopicUrl((int) $topicModel->getId());
    }

    public function getDeleteLink(TopicInterface $topicModel): string
    {
        return $this->urlHelper->getDeleteTopicUrl((int) $topicModel->getId());
    }

    public function getIsCustomerAllowedDeleteTopics(): bool
    {
        return $this->forumData->isDeleteTopicsAllowed();
    }

    public function getLatestPostDetails(int $postId): ?\Ronald2Wing\Forum\Api\Data\PostInterface
    {
        return $this->latestModel->getLatestPostData($postId);
    }

    public function getLatestPostedDate(\Ronald2Wing\Forum\Api\Data\PostInterface $post): string
    {
        return $this->forumData->formatDateTime($post->getCreatedAt() ?? '');
    }

    public function getLatestPostedBy(\Ronald2Wing\Forum\Api\Data\PostInterface $post): string
    {
        $userId = $post->getUserId();
        return $userId ? $this->forumUser->getCustomerName($userId) : '';
    }

    public function getIsModerator(): bool
    {
        return $this->authService->isModerator();
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
}
