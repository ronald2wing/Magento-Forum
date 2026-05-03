<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Model\Repository;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Model\PostRepository;
use Ronald2Wing\Forum\Model\Post;
use Ronald2Wing\Forum\Model\PostFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Post as PostResource;
use Ronald2Wing\Forum\Model\ResourceModel\Post\CollectionFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Post\Collection;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

class PostRepositoryTest extends TestCase
{
    private PostRepository $repository;
    private PostResource $resource;
    private PostFactory $postFactory;
    private CollectionFactory $collectionFactory;
    private SearchResultsInterfaceFactory $searchResultsFactory;

    protected function setUp(): void
    {
        $this->resource = $this->createMock(PostResource::class);
        $this->postFactory = $this->createMock(PostFactory::class);
        $this->collectionFactory = $this->createMock(CollectionFactory::class);
        $this->searchResultsFactory = $this->createMock(SearchResultsInterfaceFactory::class);

        $this->repository = new PostRepository(
            $this->resource,
            $this->postFactory,
            $this->collectionFactory,
            $this->searchResultsFactory
        );
    }

    public function testGetByIdReturnsPost(): void
    {
        $post = $this->createMock(Post::class);
        $post->method('getId')->willReturn(1);

        $this->postFactory->method('create')->willReturn($post);
        $this->resource->expects($this->once())
            ->method('load')
            ->with($post, 1);

        $result = $this->repository->getById(1);
        $this->assertSame($post, $result);
    }

    public function testGetByIdThrowsExceptionWhenNotFound(): void
    {
        $post = $this->createMock(Post::class);
        $post->method('getId')->willReturn(null);

        $this->postFactory->method('create')->willReturn($post);

        $this->expectException(NoSuchEntityException::class);
        $this->repository->getById(999);
    }

    public function testSaveReturnsPost(): void
    {
        $post = $this->createMock(Post::class);
        $this->resource->expects($this->once())->method('save')->with($post);

        $result = $this->repository->save($post);
        $this->assertSame($post, $result);
    }

    public function testSaveThrowsExceptionOnFailure(): void
    {
        $post = $this->createMock(Post::class);
        $this->resource->method('save')->willThrowException(new \Exception('DB error'));

        $this->expectException(CouldNotSaveException::class);
        $this->repository->save($post);
    }

    public function testDeleteReturnsTrue(): void
    {
        $post = $this->createMock(Post::class);
        $this->resource->expects($this->once())->method('delete')->with($post);

        $result = $this->repository->delete($post);
        $this->assertTrue($result);
    }

    public function testDeleteThrowsExceptionOnFailure(): void
    {
        $post = $this->createMock(Post::class);
        $this->resource->method('delete')->willThrowException(new \Exception('DB error'));

        $this->expectException(CouldNotDeleteException::class);
        $this->repository->delete($post);
    }

    public function testDeleteByIdDeletesExistingPost(): void
    {
        $post = $this->createMock(Post::class);
        $post->method('getId')->willReturn(1);

        $this->postFactory->method('create')->willReturn($post);
        $this->resource->expects($this->once())->method('delete')->with($post);

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

    public function testGetListByTopicReturnsFilteredResults(): void
    {
        $collection = $this->createMock(Collection::class);
        $collection->expects($this->once())
            ->method('addFieldToFilter')
            ->with('topic_id', 5)
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

        $result = $this->repository->getListByTopic(5, $searchCriteria);
        $this->assertSame($searchResults, $result);
    }
}
