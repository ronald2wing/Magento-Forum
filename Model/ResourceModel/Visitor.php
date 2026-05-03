<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Visitor extends AbstractDb
{
    public const TABLE_NAME = 'forum_visitor';

    protected function _construct(): void
    {
        $this->_init(self::TABLE_NAME, 'visitor_id');
    }
}
