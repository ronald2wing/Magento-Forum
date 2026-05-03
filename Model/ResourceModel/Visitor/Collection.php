<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\ResourceModel\Visitor;

use Ronald2Wing\Forum\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'visitor_id';
    protected $_eventPrefix = 'ronald2wing_forum_visitor_collection';
    protected $_eventObject = 'visitor_collection';

    protected function _construct(): void
    {
        $this->_init(
            \Ronald2Wing\Forum\Model\Visitor::class,
            \Ronald2Wing\Forum\Model\ResourceModel\Visitor::class
        );
    }

    public function addStoreFilterToCollection(int $storeId): self
    {
        $this->getSelect()->where('main_table.store_id = ?', $storeId);
        return $this;
    }
}
