<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\ResourceModel\Forum\Grid;

use Ronald2Wing\Forum\Model\ResourceModel\Forum\Collection as ForumCollection;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Psr\Log\LoggerInterface;

class Collection extends ForumCollection implements SearchResultInterface
{
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        private string $mainTable,
        private string $eventPrefix,
        private string $eventObject,
        private string $resourceModel,
        ?\Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        ?AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    public function getAggregations(): ?\Magento\Framework\Api\Search\AggregationInterface
    {
        return $this->aggregations;
    }

    public function setAggregations($aggregations): self
    {
        $this->aggregations = $aggregations;
        return $this;
    }

    public function getAllIds(?int $limit = null, ?int $offset = null): array
    {
        return array_keys($this->getItems());
    }

    public function getSearchCriteria(): ?\Magento\Framework\Api\SearchCriteriaInterface
    {
        return null;
    }

    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria): self
    {
        return $this;
    }

    public function getTotalCount(): int
    {
        return $this->getSize();
    }

    public function setTotalCount(int $totalCount): self
    {
        return $this;
    }

    public function setItems(?array $items = null): self
    {
        return $this;
    }
}
