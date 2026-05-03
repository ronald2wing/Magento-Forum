<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Block\Adminhtml\Dashboard;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Ronald2Wing\Forum\Model\ResourceModel\Forum\CollectionFactory as ForumCollectionFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Topic\CollectionFactory as TopicCollectionFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use Ronald2Wing\Forum\Api\Data\PostInterface;

class Stats extends Template
{
    protected $_template = 'Ronald2Wing_Forum::dashboard/stats.phtml';

    public function __construct(
        Context $context,
        private readonly ForumCollectionFactory $forumCollectionFactory,
        private readonly TopicCollectionFactory $topicCollectionFactory,
        private readonly PostCollectionFactory $postCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getTotalForums(): int
    {
        return $this->forumCollectionFactory->create()->addFieldToFilter("is_deleted", 0)->getSize();
    }

    public function getTotalTopics(): int
    {
        return $this->topicCollectionFactory->create()->addFieldToFilter("is_deleted", 0)->getSize();
    }

    public function getTotalPosts(): int
    {
        return $this->postCollectionFactory->create()->addFieldToFilter("is_deleted", 0)->getSize();
    }

    public function getTotalPendingPosts(): int
    {
        return $this->postCollectionFactory->create()
            ->addFieldToFilter('status', PostInterface::STATUS_PENDING)
            ->getSize();
    }
}
