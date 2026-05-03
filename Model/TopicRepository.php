<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model;

use Ronald2Wing\Forum\Api\Data\TopicInterface;
use Ronald2Wing\Forum\Api\TopicRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class TopicRepository extends AbstractRepository implements TopicRepositoryInterface
{
    public function __construct(
        private readonly ResourceModel\Topic $resource,
        private readonly TopicFactory $topicFactory,
        private readonly ResourceModel\Topic\CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory
    ) {
        parent::__construct($searchResultsFactory);
    }

    public function save(TopicInterface $topic): TopicInterface
    {
        try {
            $this->resource->save($topic);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not save topic: %1', $e->getMessage()));
        }
        return $topic;
    }

    public function getById(int $topicId): TopicInterface
    {
        $topic = $this->topicFactory->create();
        $this->resource->load($topic, $topicId);
        if (!$topic->getId()) {
            throw new NoSuchEntityException(__('Topic with id "%1" does not exist.', $topicId));
        }
        return $topic;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        return $this->doGetList($searchCriteria, $collection);
    }

    public function delete(TopicInterface $topic): bool
    {
        try {
            $this->resource->delete($topic);
            return true;
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete topic: %1', $e->getMessage()));
        }
    }

    public function deleteById(int $topicId): bool
    {
        return $this->delete($this->getById($topicId));
    }

    public function getByUrlKey(int $forumId, string $urlKey): TopicInterface
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('forum_id', $forumId);
        $collection->addFieldToFilter('url_key', $urlKey);
        $collection->setPageSize(1);

        $topic = $collection->getFirstItem();
        if (!$topic->getId()) {
            throw new NoSuchEntityException(
                __('Topic with url key "%1" in forum "%2" does not exist.', $urlKey, $forumId)
            );
        }

        return $topic;
    }

    public function getListByForum(int $forumId, SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('forum_id', $forumId);
        return $this->doGetList($searchCriteria, $collection);
    }
}
