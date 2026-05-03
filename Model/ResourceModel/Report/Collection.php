<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\ResourceModel\Report;

use Ronald2Wing\Forum\Model\ResourceModel\AbstractCollection;
use Ronald2Wing\Forum\Model\Report;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'report_id';
    protected $_eventPrefix = 'ronald2wing_forum_report_collection';
    protected $_eventObject = 'report_collection';

    protected function _construct(): void
    {
        $this->_init(Report::class, \Ronald2Wing\Forum\Model\ResourceModel\Report::class);
    }
}
