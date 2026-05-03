<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\ResourceModel\Moderator;

use Ronald2Wing\Forum\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'moderator_id';
    protected $_eventPrefix = 'ronald2wing_forum_moderator_collection';
    protected $_eventObject = 'moderator_collection';

    protected function _construct(): void
    {
        $this->_init(
            \Ronald2Wing\Forum\Model\Moderator::class,
            \Ronald2Wing\Forum\Model\ResourceModel\Moderator::class
        );
    }
}
