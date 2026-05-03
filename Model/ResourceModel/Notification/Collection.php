<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\ResourceModel\Notification;

use Ronald2Wing\Forum\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'notification_id';
    protected $_eventPrefix = 'ronald2wing_forum_notification_collection';
    protected $_eventObject = 'notification_collection';

    protected function _construct(): void
    {
        $this->_init(
            \Ronald2Wing\Forum\Model\Notification::class,
            \Ronald2Wing\Forum\Model\ResourceModel\Notification::class
        );
    }

    public function loadByCustomerTopic(int $topicId, int $customerId): self
    {
        $this->getSelect()->where('user_id = ?', $customerId);
        $this->getSelect()->where('topic_id = ?', $topicId);
        return $this;
    }

    public function getNotifyCustomers(int $topicId, int $customerId): self
    {
        $this->getSelect()->where('user_id != ?', $customerId);
        $this->getSelect()->where('topic_id = ?', $topicId);
        return $this;
    }
}
