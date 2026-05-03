<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Ui;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Ronald2Wing\Forum\Helper\Url as UrlHelper;
use Ronald2Wing\Forum\Model\ResourceModel\Forum\CollectionFactory;

class JumpTo implements ArgumentInterface
{
    public function __construct(
        private readonly CollectionFactory $forumCollectionFactory,
        private readonly UrlHelper $urlHelper
    ) {}

    public function getAllForums(): array
    {
        return $this->forumCollectionFactory->create()->enabledOnly()->getItems();
    }

    public function getForumViewUrl(object $forum): string
    {
        return $this->urlHelper->getForumUrl($forum->getUrlKey());
    }
}
