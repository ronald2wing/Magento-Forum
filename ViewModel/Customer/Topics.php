<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Customer;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Ronald2Wing\Forum\Helper\Data as ForumData;
use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Model\ResourceModel\Topic\Collection as TopicCollection;
use Ronald2Wing\Forum\Model\ResourceModel\Topic\CollectionFactory as TopicCollectionFactory;

class Topics implements ArgumentInterface
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly ForumData $forumData,
        private readonly CustomerSession $customerSession,
        private readonly TopicCollectionFactory $topicCollectionFactory
    ) {}

    public function getAllTopics(): TopicCollection
    {
        $collection = $this->topicCollectionFactory->create();
        $customerId = $this->customerSession->getId();
        if (!$customerId) {
            return $collection->setPageSize(0);
        }
        return $collection
            ->addFieldToFilter('user_id', $customerId)
            ->enabledOnly()
            ->setOrder('created_at', 'DESC')
            ->setCurPage($this->getPageNum());
    }

    public function getPageNum(): int
    {
        return (int) $this->request->getParam(Constant::PARAM_PAGE, 1);
    }

    public function getLimit(): int
    {
        return (int) $this->request->getParam(Constant::PARAM_LIMIT, Constant::DEFAULT_PAGE_SIZE_TOPIC);
    }

    public function formatDateTime(string $dateTime): string
    {
        return $this->forumData->formatDateTime($dateTime);
    }
}
