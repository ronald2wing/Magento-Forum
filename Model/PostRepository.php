<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model;

use Ronald2Wing\Forum\Api\Data\PostInterface;
use Ronald2Wing\Forum\Api\PostRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class PostRepository extends AbstractRepository implements PostRepositoryInterface
{
    public function __construct(
        private readonly ResourceModel\Post $resource,
        private readonly PostFactory $postFactory,
        private readonly ResourceModel\Post\CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory
    ) {
        parent::__construct($searchResultsFactory);
    }

    public function save(PostInterface $post): PostInterface
    {
        try {
            $this->resource->save($post);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not save post: %1', $e->getMessage()));
        }
        return $post;
    }

    public function getById(int $postId): PostInterface
    {
        $post = $this->postFactory->create();
        $this->resource->load($post, $postId);
        if (!$post->getId()) {
            throw new NoSuchEntityException(__('Post with id "%1" does not exist.', $postId));
        }
        return $post;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        return $this->doGetList($searchCriteria, $collection);
    }

    public function delete(PostInterface $post): bool
    {
        try {
            $this->resource->delete($post);
            return true;
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete post: %1', $e->getMessage()));
        }
    }

    public function deleteById(int $postId): bool
    {
        return $this->delete($this->getById($postId));
    }

    public function getListByTopic(int $topicId, SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('topic_id', $topicId);
        return $this->doGetList($searchCriteria, $collection);
    }
}
