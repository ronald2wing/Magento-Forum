<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\ResourceModel\Forum;

use Ronald2Wing\Forum\Model\ResourceModel\AbstractCollection;
use Ronald2Wing\Forum\Model\Forum;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'forum_id';
    protected $_eventPrefix = 'ronald2wing_forum_forum_collection';
    protected $_eventObject = 'forum_collection';

    protected function _construct(): void
    {
        $this->_init(Forum::class, \Ronald2Wing\Forum\Model\ResourceModel\Forum::class);
    }

    protected function _beforeLoad(): \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
    {
        $this->addFieldToFilter('is_deleted', 0);
        return parent::_beforeLoad();
    }

    public function enabledOnly(): self
    {
        $this->addFieldToFilter('status', Forum::STATUS_ENABLED);
        return $this;
    }

    public function getById(int $forumId): ?Forum
    {
        foreach ($this->getItems() as $item) {
            if ((int) $item->getId() === $forumId) {
                return $item;
            }
        }
        return null;
    }

    public function toOptionArray(): array
    {
        return $this->_toOptionArray('forum_id', 'title');
    }

    public function addStoreFilterToCollection(int $storeId): self
    {
        $this->addFieldToFilter(
            ['store_id', 'store_id'],
            [
                ['eq' => 0],
                ['eq' => $storeId],
            ]
        );
        return $this;
    }
}
