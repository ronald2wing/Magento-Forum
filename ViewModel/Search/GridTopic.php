<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Search;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Helper\Data as ForumData;
use Ronald2Wing\Forum\Model\ResourceModel\Topic\Collection as TopicCollection;
use Ronald2Wing\Forum\Model\ResourceModel\Topic\CollectionFactory as TopicCollectionFactory;
use Ronald2Wing\Forum\Model\Service\UserProfileService;

class GridTopic implements ArgumentInterface
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly ForumData $forumData,
        private readonly TopicCollectionFactory $topicCollectionFactory,
        private readonly UserProfileService $userProfileService
    ) {}

    public function getSearchResults(): TopicCollection
    {
        $query = $this->request->getParam(Constant::SEARCH_QUERY);
        $collection = $this->topicCollectionFactory->create();

        if (!$query) {
            return $collection->setPageSize(0);
        }

        return $collection
            ->addFieldToFilter(['title', 'description'], [
                ['like' => '%' . $query . '%'],
                ['like' => '%' . $query . '%'],
            ])
            ->addFieldToFilter('is_deleted', 0)
            ->addFieldToFilter('status', TopicInterface::STATUS_ENABLED)
            ->setOrder('created_at', 'DESC')
            ->setCurPage($this->getPageNum())
            ->setPageSize($this->getLimit());
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

    public function getUserName(int $userId): string
    {
        return $this->userProfileService->getCustomerName($userId);
    }
}
