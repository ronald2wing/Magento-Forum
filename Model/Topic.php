<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model;

use Ronald2Wing\Forum\Api\Data\TopicInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;

class Topic extends AbstractModel implements TopicInterface, IdentityInterface
{
    public const CACHE_TAG = 'ronald2wing_forum_t';
    public const STATUS_PENDING = 2;

    protected $_cacheTag = self::CACHE_TAG;
    protected $_eventPrefix = 'ronald2wing_forum_topic';
    protected $_eventObject = 'topic';

    protected function _construct(): void
    {
        $this->_init(\Ronald2Wing\Forum\Model\ResourceModel\Topic::class);
    }

    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getId(): ?int
    {
        return $this->getData('topic_id') !== null ? (int) $this->getData('topic_id') : null;
    }

    public function setId(?int $id): self
    {
        return $this->setData('topic_id', $id);
    }

    public function getForumId(): ?int
    {
        return $this->getData('forum_id') !== null ? (int) $this->getData('forum_id') : null;
    }

    public function setForumId(?int $forumId): self
    {
        return $this->setData('forum_id', $forumId);
    }

    public function getUserId(): ?int
    {
        return $this->getData('user_id') !== null ? (int) $this->getData('user_id') : null;
    }

    public function setUserId(?int $userId): self
    {
        return $this->setData('user_id', $userId);
    }

    public function getTitle(): ?string
    {
        return $this->getData('title');
    }

    public function setTitle(string $title): self
    {
        return $this->setData('title', $title);
    }

    public function getDescription(): ?string
    {
        return $this->getData('description');
    }

    public function setDescription(?string $description): self
    {
        return $this->setData('description', $description);
    }

    public function getUrlKey(): ?string
    {
        return $this->getData('url_key');
    }

    public function setUrlKey(string $urlKey): self
    {
        return $this->setData('url_key', $urlKey);
    }

    public function getStatus(): int
    {
        return (int) $this->getData('status');
    }

    public function setStatus(int $status): self
    {
        return $this->setData('status', $status);
    }

    public function getIconId(): ?string
    {
        return $this->getData('icon_id');
    }

    public function setIconId(?string $iconId): self
    {
        return $this->setData('icon_id', $iconId);
    }

    public function getPriority(): int
    {
        return (int) $this->getData('priority');
    }

    public function setPriority(int $priority): self
    {
        return $this->setData('priority', $priority);
    }

    public function getStoreId(): ?int
    {
        return $this->getData('store_id') !== null ? (int) $this->getData('store_id') : null;
    }

    public function setStoreId(?int $storeId): self
    {
        return $this->setData('store_id', $storeId);
    }

    public function getMetaDescription(): ?string
    {
        return $this->getData('meta_description');
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        return $this->setData('meta_description', $metaDescription);
    }

    public function getMetaKeywords(): ?string
    {
        return $this->getData('meta_keywords');
    }

    public function setMetaKeywords(?string $metaKeywords): self
    {
        return $this->setData('meta_keywords', $metaKeywords);
    }

    public function getTotalViews(): int
    {
        return (int) $this->getData('total_views');
    }

    public function setTotalViews(int $totalViews): self
    {
        return $this->setData('total_views', $totalViews);
    }

    public function getTotalPosts(): int
    {
        return (int) $this->getData('total_posts');
    }

    public function setTotalPosts(int $totalPosts): self
    {
        return $this->setData('total_posts', $totalPosts);
    }

    public function getLastPostId(): ?int
    {
        return $this->getData('last_post_id') !== null ? (int) $this->getData('last_post_id') : null;
    }

    public function setLastPostId(?int $lastPostId): self
    {
        return $this->setData('last_post_id', $lastPostId);
    }

    public function getCreatedAt(): ?string
    {
        return $this->getData('created_at');
    }

    public function setCreatedAt(string $createdAt): self
    {
        return $this->setData('created_at', $createdAt);
    }

    public function getUpdatedAt(): ?string
    {
        return $this->getData('updated_at');
    }

    public function setUpdatedAt(string $updatedAt): self
    {
        return $this->setData('updated_at', $updatedAt);
    }

    public function getIsDeleted(): bool
    {
        return (bool) $this->getData('is_deleted');
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        return $this->setData('is_deleted', $isDeleted);
    }

    public function getIsSticky(): bool
    {
        return (bool) $this->getData('is_sticky');
    }

    public function setIsSticky(bool $isSticky): self
    {
        return $this->setData('is_sticky', $isSticky);
    }


}
