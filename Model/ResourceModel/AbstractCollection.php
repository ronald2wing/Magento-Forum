<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection as BaseCollection;
use Magento\Store\Model\StoreManagerInterface;

abstract class AbstractCollection extends BaseCollection
{
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        protected readonly StoreManagerInterface $storeManager,
        ?\Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        ?\Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }
}
