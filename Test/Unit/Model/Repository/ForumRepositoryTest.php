<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Model\Repository;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Model\ForumRepository;
use Ronald2Wing\Forum\Model\Forum;
use Ronald2Wing\Forum\Model\ForumFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Forum as ForumResource;
use Ronald2Wing\Forum\Model\ResourceModel\Forum\CollectionFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Forum\Collection;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterGroup;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

class ForumRepositoryTest extends TestCase
{
    private ForumRepository $repository;
    private ForumResource $resource;
    private ForumFactory $forumFactory;
    private CollectionFactory $collectionFactory;
    private SearchResultsInterfaceFactory $searchResultsFactory;

    protected function setUp(): void
    {
        $this->resource = $this->createMock(ForumResource::class);
        $this->forumFactory = $this->createMock(ForumFactory::class);
        $this->collectionFactory = $this->createMock(CollectionFactory::class);
        $this->searchResultsFactory = $this->createMock(SearchResultsInterfaceFactory::class);

        $this->repository = new ForumRepository(
            $this->resource,
            $this->forumFactory,
            $this->collectionFactory,
            $this->searchResultsFactory
        );
    }

    public function testGetByIdReturnsForum(): void
    {
        $forum = $this->createMock(Forum::class);
        $forum->method('getId')->willReturn(1);

        $this->forumFactory->method('create')->willReturn($forum);
        $this->resource->expects($this->once())
            ->method('load')
            ->with($forum, 1);

        $result = $this->repository->getById(1);
        $this->assertSame($forum, $result);
    }

    public function testGetByIdThrowsExceptionWhenNotFound(): void
    {
        $forum = $this->createMock(Forum::class);
        $forum->method('getId')->willReturn(null);

        $this->forumFactory->method('create')->willReturn($forum);

        $this->expectException(NoSuchEntityException::class);
        $this->repository->getById(999);
    }

    public function testSaveReturnsForum(): void
    {
        $forum = $this->createMock(Forum::class);
        $this->resource->expects($this->once())->method('save')->with($forum);

        $result = $this->repository->save($forum);
        $this->assertSame($forum, $result);
    }

    public function testSaveThrowsExceptionOnFailure(): void
    {
        $forum = $this->createMock(Forum::class);
        $this->resource->method('save')->willThrowException(new \Exception('DB error'));

        $this->expectException(CouldNotSaveException::class);
        $this->repository->save($forum);
    }

    public function testDeleteReturnsTrue(): void
    {
        $forum = $this->createMock(Forum::class);
        $this->resource->expects($this->once())->method('delete')->with($forum);

        $result = $this->repository->delete($forum);
        $this->assertTrue($result);
    }

    public function testDeleteThrowsExceptionOnFailure(): void
    {
        $forum = $this->createMock(Forum::class);
        $this->resource->method('delete')->willThrowException(new \Exception('DB error'));

        $this->expectException(CouldNotDeleteException::class);
        $this->repository->delete($forum);
    }

    public function testDeleteByIdDeletesExistingForum(): void
    {
        $forum = $this->createMock(Forum::class);
        $forum->method('getId')->willReturn(1);

        $this->forumFactory->method('create')->willReturn($forum);
        $this->resource->expects($this->once())->method('delete')->with($forum);

        $result = $this->repository->deleteById(1);
        $this->assertTrue($result);
    }

    public function testDeleteByIdThrowsWhenForumNotFound(): void
    {
        $forum = $this->createMock(Forum::class);
        $forum->method('getId')->willReturn(null);

        $this->forumFactory->method('create')->willReturn($forum);

        $this->expectException(NoSuchEntityException::class);
        $this->repository->deleteById(999);
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

    public function testGetListAppliesFilters(): void
    {
        $filter = $this->createMock(Filter::class);
        $filter->method('getField')->willReturn('status');
        $filter->method('getConditionType')->willReturn('eq');
        $filter->method('getValue')->willReturn(1);

        $filterGroup = $this->createMock(FilterGroup::class);
        $filterGroup->method('getFilters')->willReturn([$filter]);

        $collection = $this->createMock(Collection::class);
        $collection->expects($this->once())
            ->method('addFieldToFilter')
            ->with('status', ['eq' => 1])
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
        $searchCriteria->method('getFilterGroups')->willReturn([$filterGroup]);
        $searchCriteria->method('getCurrentPage')->willReturn(1);
        $searchCriteria->method('getPageSize')->willReturn(10);

        $result = $this->repository->getList($searchCriteria);
        $this->assertSame($searchResults, $result);
    }
}
