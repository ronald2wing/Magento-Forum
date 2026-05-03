<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Ui;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Ronald2Wing\Forum\Model\ResourceModel\Visitor\CollectionFactory;

class WhoIsOnline implements ArgumentInterface
{
    public function __construct(
        private readonly CollectionFactory $visitorCollectionFactory,
        private readonly CustomerSession $customerSession
    ) {}

    public function getTotalUsers(): int
    {
        return $this->visitorCollectionFactory->create()->getSize();
    }

    public function getLoggedInOnly(): int
    {
        $collection = $this->visitorCollectionFactory->create();
        $collection->addFieldToFilter('user_id', ['notnull' => true]);
        return $collection->getSize();
    }

    public function getQuestsOnly(): int
    {
        return $this->getTotalUsers() - $this->getLoggedInOnly();
    }
}
