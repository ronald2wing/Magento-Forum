<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Model\Repository;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Model\TopicRepository;
use Ronald2Wing\Forum\Model\Topic;
use Ronald2Wing\Forum\Model\TopicFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Topic as TopicResource;
use Ronald2Wing\Forum\Model\ResourceModel\Topic\CollectionFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Topic\Collection;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterGroup;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

class TopicRepositoryTest extends TestCase
{
    private TopicRepository $repository;
    private TopicResource $resource;
    private TopicFactory $topicFactory;
    private CollectionFactory $collectionFactory;
    private SearchResultsInterfaceFactory $searchResultsFactory;

    protected function setUp(): void
    {
        $this->resource = $this->createMock(TopicResource::class);
        $this->topicFactory = $this->createMock(TopicFactory::class);
        $this->collectionFactory = $this->createMock(CollectionFactory::class);
        $this->searchResultsFactory = $this->createMock(SearchResultsInterfaceFactory::class);

        $this->repository = new TopicRepository(
            $this->resource,
            $this->topicFactory,
            $this->collectionFactory,
            $this->searchResultsFactory
        );
    }

    public function testGetByIdReturnsTopic(): void
    {
        $topic = $this->createMock(Topic::class);
        $topic->method('getId')->willReturn(1);

        $this->topicFactory->method('create')->willReturn($topic);
        $this->resource->expects($this->once())
            ->method('load')
            ->with($topic, 1);

        $result = $this->repository->getById(1);
        $this->assertSame($topic, $result);
    }

    public function testGetByIdThrowsExceptionWhenNotFound(): void
    {
        $topic = $this->createMock(Topic::class);
        $topic->method('getId')->willReturn(null);

        $this->topicFactory->method('create')->willReturn($topic);

        $this->expectException(NoSuchEntityException::class);
        $this->repository->getById(999);
    }

    public function testSaveReturnsTopic(): void
    {
        $topic = $this->createMock(Topic::class);
        $this->resource->expects($this->once())->method('save')->with($topic);

        $result = $this->repository->save($topic);
        $this->assertSame($topic, $result);
    }

    public function testSaveThrowsExceptionOnFailure(): void
    {
        $topic = $this->createMock(Topic::class);
        $this->resource->method('save')->willThrowException(new \Exception('DB error'));

        $this->expectException(CouldNotSaveException::class);
        $this->repository->save($topic);
    }

    public function testDeleteReturnsTrue(): void
    {
        $topic = $this->createMock(Topic::class);
        $this->resource->expects($this->once())->method('delete')->with($topic);

        $result = $this->repository->delete($topic);
        $this->assertTrue($result);
    }

    public function testDeleteThrowsExceptionOnFailure(): void
    {
        $topic = $this->createMock(Topic::class);
        $this->resource->method('delete')->willThrowException(new \Exception('DB error'));

        $this->expectException(CouldNotDeleteException::class);
        $this->repository->delete($topic);
    }

    public function testDeleteByIdDeletesExistingTopic(): void
    {
        $topic = $this->createMock(Topic::class);
        $topic->method('getId')->willReturn(1);

        $this->topicFactory->method('create')->willReturn($topic);
        $this->resource->expects($this->once())->method('delete')->with($topic);

        $result = $this->repository->deleteById(1);
        $this->assertTrue($result);
    }

    public function testGetListReturnsSearchResults(): void
    {
        $collection = $this->createMock(Collection::class);
        $collection->method('addFieldToFilter')->willReturnSelf();
        $collection->method('setCurPage')->willReturnSelf();
        $collection->method('setPageSize')->willReturnSelf();
        $collection->method('getItems')->willReturn([]);
        $collection->method('getSize')->willReturn(0);

        $searchResults = $this->createMock(SearchResults::class);
        $searchResults->expects($this->once())->method('setSearchCriteria');
        $searchResults->expects($this->once())->method('setItems')->with([]);
        $searchResults->expects($this->once())->method('setTotalCount')->with(0);

        $this->collectionFactory->method('create')->willReturn($collection);
        $this->searchResultsFactory->method('create')->willReturn($searchResults);

        $searchCriteria = $this->createMock(SearchCriteriaInterface::class);
        $searchCriteria->method('getFilterGroups')->willReturn([]);
        $searchCriteria->method('getCurrentPage')->willReturn(1);
        $searchCriteria->method('getPageSize')->willReturn(10);

        $result = $this->repository->getList($searchCriteria);
        $this->assertSame($searchResults, $result);
    }

    public function testGetByUrlKeyReturnsTopic(): void
    {
        $topic = $this->createMock(Topic::class);
        $topic->method('getId')->willReturn(1);

        $collection = $this->createMock(Collection::class);
        $collection->method('addFieldToFilter')->willReturnSelf();
        $collection->method('setPageSize')->willReturnSelf();
        $collection->method('getFirstItem')->willReturn($topic);

        $this->collectionFactory->method('create')->willReturn($collection);

        $result = $this->repository->getByUrlKey(5, 'my-topic');
        $this->assertSame($topic, $result);
    }

    public function testGetByUrlKeyThrowsExceptionWhenNotFound(): void
    {
        $topic = $this->createMock(Topic::class);
        $topic->method('getId')->willReturn(null);

        $collection = $this->createMock(Collection::class);
        $collection->method('addFieldToFilter')->willReturnSelf();
        $collection->method('setPageSize')->willReturnSelf();
        $collection->method('getFirstItem')->willReturn($topic);

        $this->collectionFactory->method('create')->willReturn($collection);

        $this->expectException(NoSuchEntityException::class);
        $this->repository->getByUrlKey(5, 'nonexistent');
    }

    public function testGetListByForumReturnsFilteredResults(): void
    {
        $collection = $this->createMock(Collection::class);
        $collection->expects($this->once())
            ->method('addFieldToFilter')
            ->with('forum_id', 5)
            ->willReturnSelf();
        $collection->method('setCurPage')->willReturnSelf();
        $collection->method('setPageSize')->willReturnSelf();
        $collection->method('getItems')->willReturn([]);
        $collection->method('getSize')->willReturn(0);

        $searchResults = $this->createMock(SearchResults::class);
        $searchResults->method('setSearchCriteria');
        $searchResults->method('setItems');
        $searchResults->method('setTotalCount');

        $this->collectionFactory->method('create')->willReturn($collection);
        $this->searchResultsFactory->method('create')->willReturn($searchResults);

        $searchCriteria = $this->createMock(SearchCriteriaInterface::class);
        $searchCriteria->method('getFilterGroups')->willReturn([]);
        $searchCriteria->method('getCurrentPage')->willReturn(1);
        $searchCriteria->method('getPageSize')->willReturn(10);

        $result = $this->repository->getListByForum(5, $searchCriteria);
        $this->assertSame($searchResults, $result);
    }
}
