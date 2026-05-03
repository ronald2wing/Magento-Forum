<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Ui;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Ronald2Wing\Forum\Model\ResourceModel\Forum\CollectionFactory as ForumCollectionFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Topic\CollectionFactory as TopicCollectionFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;

class Statistic implements ArgumentInterface
{
    public function __construct(
        private readonly ForumCollectionFactory $forumCollectionFactory,
        private readonly TopicCollectionFactory $topicCollectionFactory,
        private readonly PostCollectionFactory $postCollectionFactory
    ) {}

    public function getTotalForums(): int
    {
        return $this->forumCollectionFactory->create()->enabledOnly()->getSize();
    }

    public function getTotalTopics(): int
    {
        return $this->topicCollectionFactory->create()->enabledOnly()->getSize();
    }

    public function getTotalPosts(): int
    {
        return $this->postCollectionFactory->create()->enabledOnly()->getSize();
    }
}
