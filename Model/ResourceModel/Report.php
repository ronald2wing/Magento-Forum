<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class Report extends AbstractDb
{
    public const TABLE_NAME = 'forum_report';

    public function __construct(
        Context $context,
        ?string $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    protected function _construct(): void
    {
        $this->_init(self::TABLE_NAME, 'report_id');
    }
}
