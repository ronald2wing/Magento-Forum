<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\Service;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Ronald2Wing\Forum\Model\VisitorFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Visitor as VisitorResource;
use Ronald2Wing\Forum\Model\ResourceModel\Visitor\CollectionFactory as VisitorCollectionFactory;

class VisitorTracker
{
    private const CUTOFF_MINUTES = 5;

    public function __construct(
        private readonly VisitorFactory $visitorFactory,
        private readonly VisitorResource $visitorResource,
        private readonly VisitorCollectionFactory $visitorCollectionFactory,
        private readonly CustomerSession $customerSession,
        private readonly SessionManagerInterface $forumSession,
        private readonly DateTime $dateTime
    ) {}

    public function registerVisitation(int $forumId, int $topicId, int $parentId): void
    {
        $this->cleanOldEntries();

        $sessionId = $this->forumSession->getSessionId();

        $collection = $this->visitorCollectionFactory->create();
        $collection->addFieldToFilter('session_id', $sessionId);

        if ($collection->getSize() > 0) {
            $visitor = $collection->getFirstItem();
        } else {
            $visitor = $this->visitorFactory->create();
        }

        $visitor->setData('session_id', $sessionId);
        $visitor->setData('forum_id', $forumId);
        $visitor->setData('topic_id', $topicId);
        
        $visitor->setData('visited_at', $this->dateTime->gmtDate());

        $customerId = $this->customerSession->getCustomerId();
        if ($customerId) {
            $visitor->setData('user_id', $customerId);
        }

        $this->visitorResource->save($visitor);
    }

    private function cleanOldEntries(): void
    {
        $cutoff = $this->dateTime->gmtDate('Y-m-d H:i:s', strtotime('-' . self::CUTOFF_MINUTES . ' minutes'));
        $collection = $this->visitorCollectionFactory->create();
        $collection->addFieldToFilter('visited_at', ['lt' => $cutoff]);

        foreach ($collection as $visitor) {
            $this->visitorResource->delete($visitor);
        }
    }
}
