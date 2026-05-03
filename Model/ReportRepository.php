<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model;

use Ronald2Wing\Forum\Api\Data\ReportInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class ReportRepository
{
    public function __construct(
        private readonly ResourceModel\Report $resource,
        private readonly ReportFactory $reportFactory,
        private readonly ResourceModel\Report\CollectionFactory $collectionFactory
    ) {}

    public function save(ReportInterface $report): ReportInterface
    {
        try {
            $this->resource->save($report);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not save report: %1', $e->getMessage()));
        }
        return $report;
    }

    public function getById(int $reportId): ReportInterface
    {
        $report = $this->reportFactory->create();
        $this->resource->load($report, $reportId);
        if (!$report->getId()) {
            throw new NoSuchEntityException(__('Report with id "%1" does not exist.', $reportId));
        }
        return $report;
    }

    public function delete(ReportInterface $report): bool
    {
        try {
            $this->resource->delete($report);
            return true;
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete report: %1', $e->getMessage()));
        }
    }

    public function deleteById(int $reportId): bool
    {
        return $this->delete($this->getById($reportId));
    }
}
