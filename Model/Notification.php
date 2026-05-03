<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model;

use Magento\Framework\Model\AbstractModel;

class Notification extends AbstractModel
{
    protected $_eventPrefix = 'ronald2wing_forum_notification';

    protected function _construct(): void
    {
        $this->_init(\Ronald2Wing\Forum\Model\ResourceModel\Notification::class);
    }

    public function getId(): ?int
    {
        return $this->getData('notification_id') !== null ? (int) $this->getData('notification_id') : null;
    }

    public function setId(?int $id): self
    {
        return $this->setData('notification_id', $id);
    }

    public function getUserId(): ?int
    {
        return $this->getData('user_id') !== null ? (int) $this->getData('user_id') : null;
    }

    public function setUserId(int $userId): self
    {
        return $this->setData('user_id', $userId);
    }

    public function getTopicId(): ?int
    {
        return $this->getData('topic_id') !== null ? (int) $this->getData('topic_id') : null;
    }

    public function setTopicId(int $topicId): self
    {
        return $this->setData('topic_id', $topicId);
    }

    public function getUnsubscribeHash(): ?string
    {
        return $this->getData('unsubscribe_hash');
    }

    public function setUnsubscribeHash(string $unsubscribeHash): self
    {
        return $this->setData('unsubscribe_hash', $unsubscribeHash);
    }
}
