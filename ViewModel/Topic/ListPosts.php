<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Topic;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;
use Ronald2Wing\Forum\Helper\Data as ForumData;
use Ronald2Wing\Forum\Helper\Url as UrlHelper;
use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Model\Icon;
use Ronald2Wing\Forum\Api\Data\PostInterface;
use Ronald2Wing\Forum\Api\Data\TopicInterface;
use Ronald2Wing\Forum\Model\Service\UserProfileService;
use Ronald2Wing\Forum\Model\ResourceModel\Post\Collection as PostCollection;

class ListPosts implements ArgumentInterface
{
    private array $loadedUsers = [];

    public function __construct(
        private readonly ForumData $forumData,
        private readonly UrlHelper $urlHelper,
        private readonly Icon $icon,
        private readonly UserProfileService $forumUser,
        private readonly UrlInterface $url,
        private readonly CustomerSession $customerSession,
        private readonly StoreManagerInterface $storeManager,
        private readonly Index $topicIndexViewModel
    ) {}

    public function getIsIconsEnabled(): bool
    {
        return $this->forumData->isIconsAllowed();
    }

    public function getIconSrc(?TopicInterface $model = null): string
    {
        $entity = $model ?: $this->topicIndexViewModel->getParentTopic();
        if (!$entity || !$entity->getIconId()) {
            return '';
        }
        return $this->icon->getIconUrl($entity->getIconId(), $this->storeManager);
    }

    public function getDisplayPosts(PostCollection $collection, int $pageLimit, int $pageNum): PostCollection
    {
        $collection->setPageSize($pageLimit);
        $collection->setCurPage($pageNum);
        return $collection;
    }

    public function getSortUrl(string $type = 'asc'): string
    {
        return $this->url->getUrl('*/*/*', [
            Constant::PARAM_SORT => $type,
            Constant::PARAM_PAGE => 1,
        ]);
    }

    public function getUserDetails(int $userId): array
    {
        if (!isset($this->loadedUsers[$userId])) {
            $settings = $this->forumUser->getUserSettings($userId);
            $avatarUrl = $this->forumUser->getAvatarUrl($settings);
            $this->loadedUsers[$userId] = [
                'nickname' => $settings->getNickname() ?: $this->forumUser->getCustomerName($userId),
                'avatar' => $avatarUrl,
                'signature' => $settings->getSignature(),
                'role' => $this->forumUser->getRole($userId),
                'total_topics' => $this->forumUser->getTotalTopics($userId),
                'total_posts' => $this->forumUser->getTotalPosts($userId),
            ];
        }
        return $this->loadedUsers[$userId];
    }

    public function getTopicTitle(): string
    {
        return $this->topicIndexViewModel->getParentTopic()?->getTitle() ?? '';
    }

    public function getPostTime(PostInterface $postModel): string
    {
        return $this->forumData->formatDateTime($postModel->getCreatedAt() ?? '');
    }

    public function getAddNewUrl(): string
    {
        return $this->urlHelper->getForumUrl();
    }

    public function getAddPostNewUrl(): string
    {
        return $this->urlHelper->getForumUrl();
    }

    public function getEditPostUrl(PostInterface $post): string
    {
        return $this->urlHelper->getForumUrl();
    }

    public function getDeletePostUrl(PostInterface $post): string
    {
        return $this->url->getUrl(Constant::FRONTEND_ROUTE_NAME . '/post/delete', [
            Constant::PARAM_POST_ID => $post->getId(),
        ]);
    }

    public function getReportPostUrl(PostInterface $post): string
    {
        return $this->url->getUrl(Constant::FRONTEND_ROUTE_NAME . '/post/report', [
            Constant::PARAM_POST_ID => $post->getId(),
        ]);
    }

    public function getActionPost(): string
    {
        return $this->url->getUrl(Constant::FRONTEND_ROUTE_NAME . '/topic/save');
    }

    public function getForumIdParamName(): string
    {
        return Constant::PARAM_FORUM_ID;
    }

    public function getTopicIdParamName(): string
    {
        return Constant::PARAM_TOPIC_ID;
    }

    public function getPostBlockId(PostInterface $postModel): string
    {
        return Constant::POST_BLOCK_ID_PREFIX . $postModel->getId();
    }

    public function getIsCustomerNotificationEnabled(): bool
    {
        return true;
    }
}
