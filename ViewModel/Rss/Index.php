<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Rss;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Registry;
use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Helper\Data as ForumData;
use Ronald2Wing\Forum\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;

class Index implements ArgumentInterface
{
    public function __construct(
        private readonly Registry $registry,
        private readonly ForumData $forumData,
        private readonly PostCollectionFactory $postCollectionFactory
    ) {}

    public function getRssTopic(): ?array
    {
        return $this->registry->registry(Constant::REGISTRY_RSS_TOPIC);
    }

    public function getRssForums(): ?array
    {
        return $this->registry->registry(Constant::REGISTRY_RSS_FORUMS);
    }

    public function formatDateTime(string $dateTime): string
    {
        return $this->forumData->formatDateTime($dateTime);
    }
}
