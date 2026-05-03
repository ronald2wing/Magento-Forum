<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Model\Repository;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Model\ModeratorRepository;
use Ronald2Wing\Forum\Model\Moderator;
use Ronald2Wing\Forum\Model\ModeratorFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Moderator as ModeratorResource;
use Ronald2Wing\Forum\Model\ResourceModel\Moderator\CollectionFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Moderator\Collection;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

class ModeratorRepositoryTest extends TestCase
{
    private ModeratorRepository $repository;
    private ModeratorResource $resource;
    private ModeratorFactory $moderatorFactory;
    private CollectionFactory $collectionFactory;
    private SearchResultsInterfaceFactory $searchResultsFactory;

    protected function setUp(): void
    {
        $this->resource = $this->createMock(ModeratorResource::class);
        $this->moderatorFactory = $this->createMock(ModeratorFactory::class);
        $this->collectionFactory = $this->createMock(CollectionFactory::class);
        $this->searchResultsFactory = $this->createMock(SearchResultsInterfaceFactory::class);

        $this->repository = new ModeratorRepository(
            $this->resource,
            $this->moderatorFactory,
            $this->collectionFactory,
            $this->searchResultsFactory
        );
    }

    public function testGetByIdReturnsModerator(): void
    {
        $moderator = $this->createMock(Moderator::class);
        $moderator->method('getId')->willReturn(1);

        $this->moderatorFactory->method('create')->willReturn($moderator);
        $this->resource->expects($this->once())
            ->method('load')
            ->with($moderator, 1);

        $result = $this->repository->getById(1);
        $this->assertSame($moderator, $result);
    }

    public function testGetByIdThrowsExceptionWhenNotFound(): void
    {
        $moderator = $this->createMock(Moderator::class);
        $moderator->method('getId')->willReturn(null);

        $this->moderatorFactory->method('create')->willReturn($moderator);

        $this->expectException(NoSuchEntityException::class);
        $this->repository->getById(999);
    }

    public function testSaveReturnsModerator(): void
    {
        $moderator = $this->createMock(Moderator::class);
        $this->resource->expects($this->once())->method('save')->with($moderator);

        $result = $this->repository->save($moderator);
        $this->assertSame($moderator, $result);
    }

    public function testSaveThrowsExceptionOnFailure(): void
    {
        $moderator = $this->createMock(Moderator::class);
        $this->resource->method('save')->willThrowException(new \Exception('DB error'));

        $this->expectException(CouldNotSaveException::class);
        $this->repository->save($moderator);
    }

    public function testDeleteReturnsTrue(): void
    {
        $moderator = $this->createMock(Moderator::class);
        $this->resource->expects($this->once())->method('delete')->with($moderator);

        $result = $this->repository->delete($moderator);
        $this->assertTrue($result);
    }

    public function testDeleteThrowsExceptionOnFailure(): void
    {
        $moderator = $this->createMock(Moderator::class);
        $this->resource->method('delete')->willThrowException(new \Exception('DB error'));

        $this->expectException(CouldNotDeleteException::class);
        $this->repository->delete($moderator);
    }

    public function testDeleteByIdDeletesExistingModerator(): void
    {
        $moderator = $this->createMock(Moderator::class);
        $moderator->method('getId')->willReturn(1);

        $this->moderatorFactory->method('create')->willReturn($moderator);
        $this->resource->expects($this->once())->method('delete')->with($moderator);

        $result = $this->repository->deleteById(1);
        $this->assertTrue($result);
    }

    public function testIsModeratorReturnsTrueWhenExists(): void
    {
        $collection = $this->createMock(Collection::class);
        $collection->method('addFieldToFilter')->with('user_id', 42)->willReturnSelf();
        $collection->method('getSize')->willReturn(1);

        $this->collectionFactory->method('create')->willReturn($collection);

        $result = $this->repository->isModerator(42);
        $this->assertTrue($result);
    }

    public function testIsModeratorReturnsFalseWhenNotExists(): void
    {
        $collection = $this->createMock(Collection::class);
        $collection->method('addFieldToFilter')->with('user_id', 99)->willReturnSelf();
        $collection->method('getSize')->willReturn(0);

        $this->collectionFactory->method('create')->willReturn($collection);

        $result = $this->repository->isModerator(99);
        $this->assertFalse($result);
    }

    public function testAddModeratorCreatesAndSaves(): void
    {
        $moderator = $this->createMock(Moderator::class);
        $moderator->expects($this->once())->method('setUserId')->with(42);
        $moderator->expects($this->once())->method('setWebsiteId')->with(1);

        $this->moderatorFactory->method('create')->willReturn($moderator);
        $this->resource->expects($this->once())->method('save')->with($moderator);

        $result = $this->repository->addModerator(42, 1);
        $this->assertSame($moderator, $result);
    }

    public function testAddModeratorWithNullWebsiteId(): void
    {
        $moderator = $this->createMock(Moderator::class);
        $moderator->expects($this->once())->method('setUserId')->with(42);
        $moderator->expects($this->once())->method('setWebsiteId')->with(null);

        $this->moderatorFactory->method('create')->willReturn($moderator);
        $this->resource->expects($this->once())->method('save')->with($moderator);

        $result = $this->repository->addModerator(42, null);
        $this->assertSame($moderator, $result);
    }

    public function testAddModeratorThrowsExceptionOnSaveFailure(): void
    {
        $moderator = $this->createMock(Moderator::class);
        $moderator->method('setUserId');
        $moderator->method('setWebsiteId');

        $this->moderatorFactory->method('create')->willReturn($moderator);
        $this->resource->method('save')->willThrowException(new \Exception('DB error'));

        $this->expectException(CouldNotSaveException::class);
        $this->repository->addModerator(42, null);
    }

    public function testRemoveModeratorDeletesExisting(): void
    {
        $moderator = $this->createMock(Moderator::class);
        $moderator->method('getId')->willReturn(5);

        $collection = $this->createMock(Collection::class);
        $collection->method('addFieldToFilter')->willReturnSelf();
        $collection->method('getFirstItem')->willReturn($moderator);

        $this->collectionFactory->method('create')->willReturn($collection);
        $this->resource->expects($this->once())->method('delete')->with($moderator);

        $result = $this->repository->removeModerator(42);
        $this->assertTrue($result);
    }

    public function testRemoveModeratorThrowsExceptionWhenNotFound(): void
    {
        $moderator = $this->createMock(Moderator::class);
        $moderator->method('getId')->willReturn(null);

        $collection = $this->createMock(Collection::class);
        $collection->method('addFieldToFilter')->willReturnSelf();
        $collection->method('getFirstItem')->willReturn($moderator);

        $this->collectionFactory->method('create')->willReturn($collection);

        $this->expectException(NoSuchEntityException::class);
        $this->repository->removeModerator(99);
    }
}
