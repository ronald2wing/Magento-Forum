<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Block\Adminhtml\Report;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Ronald2Wing\Forum\Model\ResourceModel\Report\CollectionFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Report\Collection;

class Listing extends Template
{
    protected $_template = 'Ronald2Wing_Forum::report/listing.phtml';

    public function __construct(
        Context $context,
        private readonly CollectionFactory $reportCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getReports(): Collection
    {
        return $this->reportCollectionFactory->create()
            ->setOrder('created_at', 'DESC');
    }

    public function getStatusLabel(int $status): string
    {
        return $status === \Ronald2Wing\Forum\Api\Data\ReportInterface::STATUS_NEW ? (string) __('New') : (string) __('Reviewed');
    }
}
