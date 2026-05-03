<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model;

use Ronald2Wing\Forum\Api\Data\PostInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;

class Post extends AbstractModel implements PostInterface, IdentityInterface
{
    public const CACHE_TAG = 'ronald2wing_forum_p';
    public const STATUS_PENDING = 2;

    protected $_cacheTag = self::CACHE_TAG;
    protected $_eventPrefix = 'ronald2wing_forum_post';
    protected $_eventObject = 'post';

    protected function _construct(): void
    {
        $this->_init(\Ronald2Wing\Forum\Model\ResourceModel\Post::class);
    }

    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getId(): ?int
    {
        return $this->getData('post_id') !== null ? (int) $this->getData('post_id') : null;
    }

    public function setId(?int $id): self
    {
        return $this->setData('post_id', $id);
    }

    public function getTopicId(): ?int
    {
        return $this->getData('topic_id') !== null ? (int) $this->getData('topic_id') : null;
    }

    public function setTopicId(int $topicId): self
    {
        return $this->setData('topic_id', $topicId);
    }

    public function getForumId(): ?int
    {
        return $this->getData('forum_id') !== null ? (int) $this->getData('forum_id') : null;
    }

    public function setForumId(int $forumId): self
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

    public function getContent(): ?string
    {
        return $this->getData('content');
    }

    public function setContent(string $content): self
    {
        return $this->setData('content', $content);
    }

    public function getContentOriginal(): ?string
    {
        return $this->getData('content_original');
    }

    public function setContentOriginal(?string $contentOriginal): self
    {
        return $this->setData('content_original', $contentOriginal);
    }

    public function getStatus(): int
    {
        return (int) $this->getData('status');
    }

    public function setStatus(int $status): self
    {
        return $this->setData('status', $status);
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
