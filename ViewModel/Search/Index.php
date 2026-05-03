<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Search;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Helper\Data as ForumData;
use Ronald2Wing\Forum\Model\ResourceModel\Post\Collection as PostCollection;
use Ronald2Wing\Forum\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;

class Index implements ArgumentInterface
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly ForumData $forumData,
        private readonly PostCollectionFactory $postCollectionFactory
    ) {}

    public function getSearchQuery(): string
    {
        return (string) $this->request->getParam(Constant::SEARCH_QUERY, '');
    }

    public function getSearchType(): string
    {
        return $this->request->getParam(Constant::SEARCH_TYPE, Constant::SEARCH_TYPE_POST);
    }

    public function formatDateTime(string $dateTime): string
    {
        return $this->forumData->formatDateTime($dateTime);
    }
}
