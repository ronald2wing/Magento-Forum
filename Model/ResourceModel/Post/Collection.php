<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\ResourceModel\Post;

use Ronald2Wing\Forum\Model\ResourceModel\AbstractCollection;
use Ronald2Wing\Forum\Model\Post;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'post_id';
    protected $_eventPrefix = 'ronald2wing_forum_post_collection';
    protected $_eventObject = 'post_collection';

    protected function _construct(): void
    {
        $this->_init(Post::class, \Ronald2Wing\Forum\Model\ResourceModel\Post::class);
    }

    protected function _beforeLoad(): \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
    {
        $this->addFieldToFilter('is_deleted', 0);
        return parent::_beforeLoad();
    }

    public function enabledOnly(): self
    {
        $this->addFieldToFilter('status', Post::STATUS_ENABLED);
        return $this;
    }

    public function notDeleted(): self
    {
        $this->addFieldToFilter('is_deleted', 0);
        return $this;
    }
}
