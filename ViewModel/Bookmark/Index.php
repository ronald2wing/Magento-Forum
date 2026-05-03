<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Bookmark;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Helper\Data as ForumData;
use Ronald2Wing\Forum\Model\ResourceModel\Topic\Collection as TopicCollection;
use Ronald2Wing\Forum\Model\ResourceModel\Topic\CollectionFactory as TopicCollectionFactory;
use Ronald2Wing\Forum\Model\Service\UserProfileService;

class Index implements ArgumentInterface
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly ForumData $forumData,
        private readonly TopicCollectionFactory $topicCollectionFactory,
        private readonly UserProfileService $userProfileService
    ) {}

    public function getBookmarkedTopics(): TopicCollection
    {
        $bookmarkIds = $this->request->getParam(Constant::BOOKMARK_TOPIC_IDS);
        $collection = $this->topicCollectionFactory->create();

        if (!$bookmarkIds) {
            return $collection->setPageSize(0);
        }

        $ids = is_array($bookmarkIds) ? $bookmarkIds : explode(',', (string) $bookmarkIds);
        $ids = array_map('intval', $ids);
        $ids = array_filter($ids);

        if (empty($ids)) {
            return $collection->setPageSize(0);
        }

        return $collection
            ->addFieldToFilter('topic_id', ['in' => $ids])
            ->addFieldToFilter('is_deleted', 0)
            ->addFieldToFilter('status', \Ronald2Wing\Forum\Api\Data\PostInterface::STATUS_ENABLED)
            ->setOrder('created_at', 'DESC');
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
