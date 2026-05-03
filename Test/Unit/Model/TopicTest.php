<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Api\Data\TopicInterface;
use Ronald2Wing\Forum\Model\Topic;

class TopicTest extends TestCase
{
    private Topic $topic;

    protected function setUp(): void
    {
        $this->topic = $this->getMockBuilder(Topic::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_init', '_construct'])
            ->getMock();
    }

    public function testGetIdReturnsNullWhenNoData(): void
    {
        $this->assertNull($this->topic->getId());
    }

    public function testSetAndGetId(): void
    {
        $this->topic->setId(42);
        $this->assertSame(42, $this->topic->getId());
    }

    public function testSetAndGetForumId(): void
    {
        $this->topic->setForumId(5);
        $this->assertSame(5, $this->topic->getForumId());
    }

    public function testSetAndGetUserId(): void
    {
        $this->topic->setUserId(10);
        $this->assertSame(10, $this->topic->getUserId());
    }

    public function testSetAndGetTitle(): void
    {
        $this->topic->setTitle('Test Topic');
        $this->assertSame('Test Topic', $this->topic->getTitle());
    }

    public function testSetAndGetUrlKey(): void
    {
        $this->topic->setUrlKey('test-topic');
        $this->assertSame('test-topic', $this->topic->getUrlKey());
    }

    public function testStatusDefaultsToDisabled(): void
    {
        $this->assertSame(0, $this->topic->getStatus());
    }

    public function testSetStatusToEnabled(): void
    {
        $this->topic->setStatus(TopicInterface::STATUS_ENABLED);
        $this->assertSame(1, $this->topic->getStatus());
    }

    public function testSetStatusToDisabled(): void
    {
        $this->topic->setStatus(TopicInterface::STATUS_DISABLED);
        $this->assertSame(0, $this->topic->getStatus());
    }

    public function testIsStickyDefaultsToFalse(): void
    {
        $this->assertFalse($this->topic->getIsSticky());
    }

    public function testSetAndGetIsSticky(): void
    {
        $this->topic->setIsSticky(true);
        $this->assertTrue($this->topic->getIsSticky());
    }

    public function testIsDeletedDefaultsToFalse(): void
    {
        $this->assertFalse($this->topic->getIsDeleted());
    }

    public function testSetAndGetIsDeleted(): void
    {
        $this->topic->setIsDeleted(true);
        $this->assertTrue($this->topic->getIsDeleted());
    }

    public function testTotalViewsDefaultsToZero(): void
    {
        $this->assertSame(0, $this->topic->getTotalViews());
    }

    public function testSetAndGetTotalViews(): void
    {
        $this->topic->setTotalViews(150);
        $this->assertSame(150, $this->topic->getTotalViews());
    }

    public function testTotalPostsDefaultsToZero(): void
    {
        $this->assertSame(0, $this->topic->getTotalPosts());
    }

    public function testSetAndGetTotalPosts(): void
    {
        $this->topic->setTotalPosts(25);
        $this->assertSame(25, $this->topic->getTotalPosts());
    }

    public function testLastPostIdNullInitially(): void
    {
        $this->assertNull($this->topic->getLastPostId());
    }

    public function testSetAndGetLastPostId(): void
    {
        $this->topic->setLastPostId(88);
        $this->assertSame(88, $this->topic->getLastPostId());
    }

    public function testSetAndGetIconId(): void
    {
        $this->topic->setIconId('star');
        $this->assertSame('star', $this->topic->getIconId());
    }

    public function testSetAndGetPriority(): void
    {
        $this->topic->setPriority(3);
        $this->assertSame(3, $this->topic->getPriority());
    }

    public function testStoreIdDefaultNull(): void
    {
        $this->assertNull($this->topic->getStoreId());
    }

    public function testSetAndGetStoreId(): void
    {
        $this->topic->setStoreId(1);
        $this->assertSame(1, $this->topic->getStoreId());
    }

    public function testSetAndGetMetaDescription(): void
    {
        $this->topic->setMetaDescription('Topic meta description');
        $this->assertSame('Topic meta description', $this->topic->getMetaDescription());
    }

    public function testSetAndGetMetaKeywords(): void
    {
        $this->topic->setMetaKeywords('topic,test,keywords');
        $this->assertSame('topic,test,keywords', $this->topic->getMetaKeywords());
    }

    public function testSetAndGetDescription(): void
    {
        $this->topic->setDescription('A topic description');
        $this->assertSame('A topic description', $this->topic->getDescription());
    }

    public function testSetAndGetCreatedAt(): void
    {
        $timestamp = '2025-02-10 08:00:00';
        $this->topic->setCreatedAt($timestamp);
        $this->assertSame($timestamp, $this->topic->getCreatedAt());
    }

    public function testSetAndGetUpdatedAt(): void
    {
        $timestamp = '2025-02-11 12:00:00';
        $this->topic->setUpdatedAt($timestamp);
        $this->assertSame($timestamp, $this->topic->getUpdatedAt());
    }

    public function testGetIdentities(): void
    {
        $this->topic->setId(10);
        $this->assertSame(['ronald2wing_forum_t_10'], $this->topic->getIdentities());
    }

    public function testGetIdentitiesReturnsNullTagWhenNoId(): void
    {
        $this->assertSame(['ronald2wing_forum_t_'], $this->topic->getIdentities());
    }

    public function testSetAndGetProductId(): void
    {
        $this->topic->setProductId(100);
        $this->assertSame(100, $this->topic->getProductId());
    }
}
