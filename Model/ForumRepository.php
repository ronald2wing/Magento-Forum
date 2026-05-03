<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model;

use Ronald2Wing\Forum\Api\Data\ForumInterface;
use Ronald2Wing\Forum\Api\ForumRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class ForumRepository extends AbstractRepository implements ForumRepositoryInterface
{
    public function __construct(
        private readonly ResourceModel\Forum $resource,
        private readonly ForumFactory $forumFactory,
        private readonly ResourceModel\Forum\CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory
    ) {
        parent::__construct($searchResultsFactory);
    }

    public function save(ForumInterface $forum): ForumInterface
    {
        try {
            $this->resource->save($forum);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not save forum: %1', $e->getMessage()));
        }
        return $forum;
    }

    public function getById(int $forumId): ForumInterface
    {
        $forum = $this->forumFactory->create();
        $this->resource->load($forum, $forumId);
        if (!$forum->getId()) {
            throw new NoSuchEntityException(__('Forum with id "%1" does not exist.', $forumId));
        }
        return $forum;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        return $this->doGetList($searchCriteria, $collection);
    }

    public function delete(ForumInterface $forum): bool
    {
        try {
            $this->resource->delete($forum);
            return true;
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete forum: %1', $e->getMessage()));
        }
    }

    public function deleteById(int $forumId): bool
    {
        return $this->delete($this->getById($forumId));
    }
}
