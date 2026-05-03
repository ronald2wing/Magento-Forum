<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\ResourceModel\Topic;

use Ronald2Wing\Forum\Model\ResourceModel\AbstractCollection;
use Ronald2Wing\Forum\Model\Topic;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'topic_id';
    protected $_eventPrefix = 'ronald2wing_forum_topic_collection';
    protected $_eventObject = 'topic_collection';

    protected function _construct(): void
    {
        $this->_init(Topic::class, \Ronald2Wing\Forum\Model\ResourceModel\Topic::class);
    }

    protected function _beforeLoad(): \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
    {
        $this->addFieldToFilter('is_deleted', 0);
        return parent::_beforeLoad();
    }

    public function enabledOnly(): self
    {
        $this->addFieldToFilter('status', Topic::STATUS_ENABLED);
        return $this;
    }

    public function notDeleted(): self
    {
        $this->addFieldToFilter('is_deleted', 0);
        return $this;
    }

    public function getUserTopics(int $userId): self
    {
        $this->addFieldToFilter('user_id', $userId);
        return $this;
    }

    public function byForum(int $forumId): self
    {
        $this->addFieldToFilter('forum_id', $forumId);
        return $this;
    }

    public function toOptionArray(): array
    {
        return $this->_toOptionArray('topic_id', 'title');
    }
}
