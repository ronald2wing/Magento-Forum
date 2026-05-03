<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Ronald2Wing\Forum\Model\Service\CounterUpdater;

class TopicSaveAfter implements ObserverInterface
{
    public function __construct(
        private readonly CounterUpdater $counterUpdater
    ) {}

    public function execute(Observer $observer): void
    {
        $topic = $observer->getData('topic');
        if ($topic && $topic->getId()) {
            $this->counterUpdater->updateTopicCounts((int) $topic->getId());
        }
    }
}
