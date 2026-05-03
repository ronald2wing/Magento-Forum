<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model;

use Ronald2Wing\Forum\Api\Data\ReportInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;

class Report extends AbstractModel implements ReportInterface, IdentityInterface
{
    public const CACHE_TAG = 'ronald2wing_forum_report';

    protected $_cacheTag = self::CACHE_TAG;
    protected $_eventPrefix = 'ronald2wing_forum_report';
    protected $_eventObject = 'report';

    protected function _construct(): void
    {
        $this->_init(\Ronald2Wing\Forum\Model\ResourceModel\Report::class);
    }

    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getId(): ?int
    {
        return $this->getData('report_id') !== null ? (int) $this->getData('report_id') : null;
    }

    public function setId(?int $id): self
    {
        return $this->setData('report_id', $id);
    }

    public function getPostId(): ?int
    {
        return $this->getData('post_id') !== null ? (int) $this->getData('post_id') : null;
    }

    public function setPostId(int $postId): self
    {
        return $this->setData('post_id', $postId);
    }

    public function getUserId(): ?int
    {
        return $this->getData('user_id') !== null ? (int) $this->getData('user_id') : null;
    }

    public function setUserId(?int $userId): self
    {
        return $this->setData('user_id', $userId);
    }

    public function getReason(): ?string
    {
        return $this->getData('reason');
    }

    public function setReason(?string $reason): self
    {
        return $this->setData('reason', $reason);
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
}
