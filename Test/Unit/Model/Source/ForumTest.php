<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Model\Source;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Model\Source\Forum as ForumSource;
use Ronald2Wing\Forum\Model\ResourceModel\Forum\CollectionFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Forum\Collection;
use Ronald2Wing\Forum\Model\Forum;

class ForumTest extends TestCase
{
    public function testToOptionArrayReturnsEmptyForNoForums(): void
    {
        $collectionMock = $this->createMock(Collection::class);
        $collectionMock->expects($this->once())
            ->method('addFieldToFilter')
            ->with('parent_id', ['null' => true])
            ->willReturnSelf();
        $collectionMock->method('getIterator')
            ->willReturn(new \ArrayIterator([]));

        $collectionFactory = $this->createMock(CollectionFactory::class);
        $collectionFactory->method('create')->willReturn($collectionMock);

        $source = new ForumSource($collectionFactory);
        $result = $source->toOptionArray();

        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }

    public function testToOptionArrayReturnsOptionsForForums(): void
    {
        $forum1 = $this->getMockBuilder(Forum::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_init', '_construct', 'getId', 'getTitle'])
            ->getMock();
        $forum1->method('getId')->willReturn(1);
        $forum1->method('getTitle')->willReturn('General Discussion');

        $forum2 = $this->getMockBuilder(Forum::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_init', '_construct', 'getId', 'getTitle'])
            ->getMock();
        $forum2->method('getId')->willReturn(2);
        $forum2->method('getTitle')->willReturn('Help Desk');

        $collectionMock = $this->createMock(Collection::class);
        $collectionMock->expects($this->once())
            ->method('addFieldToFilter')
            ->with('parent_id', ['null' => true])
            ->willReturnSelf();
        $collectionMock->method('getIterator')
            ->willReturn(new \ArrayIterator([$forum1, $forum2]));

        $collectionFactory = $this->createMock(CollectionFactory::class);
        $collectionFactory->method('create')->willReturn($collectionMock);

        $source = new ForumSource($collectionFactory);
        $result = $source->toOptionArray();

        $this->assertCount(2, $result);
        $this->assertSame(1, $result[0]['value']);
        $this->assertSame('General Discussion', $result[0]['label']);
        $this->assertSame(2, $result[1]['value']);
        $this->assertSame('Help Desk', $result[1]['label']);
    }
}
