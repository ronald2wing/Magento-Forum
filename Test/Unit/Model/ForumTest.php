<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Api\Data\ForumInterface;
use Ronald2Wing\Forum\Model\Forum;

class ForumTest extends TestCase
{
    private Forum $forum;

    protected function setUp(): void
    {
        $this->forum = $this->getMockBuilder(Forum::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_init', '_construct'])
            ->getMock();
    }

    public function testGetIdReturnsNullWhenNoData(): void
    {
        $this->assertNull($this->forum->getId());
    }

    public function testSetAndGetId(): void
    {
        $this->forum->setId(42);
        $this->assertSame(42, $this->forum->getId());
    }

    public function testSetAndGetTitle(): void
    {
        $this->forum->setTitle('Test Forum');
        $this->assertSame('Test Forum', $this->forum->getTitle());
    }

    public function testStatusDefaultsToDisabled(): void
    {
        $this->assertSame(0, $this->forum->getStatus());
    }

    public function testSetStatusToEnabled(): void
    {
        $this->forum->setStatus(ForumInterface::STATUS_ENABLED);
        $this->assertSame(1, $this->forum->getStatus());
    }

    public function testSetStatusToDisabled(): void
    {
        $this->forum->setStatus(ForumInterface::STATUS_DISABLED);
        $this->assertSame(0, $this->forum->getStatus());
    }

    public function testIsDeletedDefaultsToFalse(): void
    {
        $this->assertFalse($this->forum->getIsDeleted());
    }

    public function testSetAndGetIsDeleted(): void
    {
        $this->forum->setIsDeleted(true);
        $this->assertTrue($this->forum->getIsDeleted());
    }

    public function testGetIdentities(): void
    {
        $this->forum->setId(1);
        $this->assertSame(['ronald2wing_forum_f_1'], $this->forum->getIdentities());
    }

    public function testGetIdentitiesReturnsNullTagWhenNoId(): void
    {
        $this->assertSame(['ronald2wing_forum_f_'], $this->forum->getIdentities());
    }

    public function testSetAndGetUrlKey(): void
    {
        $this->forum->setUrlKey('my-forum');
        $this->assertSame('my-forum', $this->forum->getUrlKey());
    }

    public function testSetAndGetMetaDescription(): void
    {
        $this->forum->setMetaDescription('Forum description');
        $this->assertSame('Forum description', $this->forum->getMetaDescription());
    }

    public function testSetAndGetMetaKeywords(): void
    {
        $this->forum->setMetaKeywords('forum,test');
        $this->assertSame('forum,test', $this->forum->getMetaKeywords());
    }

    public function testSetAndGetIconId(): void
    {
        $this->forum->setIconId('smile');
        $this->assertSame('smile', $this->forum->getIconId());
    }

    public function testSetAndGetPriority(): void
    {
        $this->forum->setPriority(5);
        $this->assertSame(5, $this->forum->getPriority());
    }

    public function testStoreIdDefaultNull(): void
    {
        $this->assertNull($this->forum->getStoreId());
    }

    public function testSetAndGetStoreId(): void
    {
        $this->forum->setStoreId(1);
        $this->assertSame(1, $this->forum->getStoreId());
    }

    public function testTotalTopicsAndPostsDefaultZero(): void
    {
        $this->assertSame(0, $this->forum->getTotalTopics());
        $this->assertSame(0, $this->forum->getTotalPosts());
    }

    public function testSetAndGetTotalCounts(): void
    {
        $this->forum->setTotalTopics(10);
        $this->forum->setTotalPosts(50);
        $this->assertSame(10, $this->forum->getTotalTopics());
        $this->assertSame(50, $this->forum->getTotalPosts());
    }

    public function testLastPostIdNullInitially(): void
    {
        $this->assertNull($this->forum->getLastPostId());
    }

    public function testSetAndGetLastPostId(): void
    {
        $this->forum->setLastPostId(99);
        $this->assertSame(99, $this->forum->getLastPostId());
    }

    public function testSetAndGetParentId(): void
    {
        $this->forum->setParentId(0);
        $this->assertSame(0, $this->forum->getParentId());
    }

    public function testSetAndGetDescription(): void
    {
        $this->forum->setDescription('A forum description');
        $this->assertSame('A forum description', $this->forum->getDescription());
    }

    public function testSetAndGetCreatedAt(): void
    {
        $timestamp = '2025-01-15 10:30:00';
        $this->forum->setCreatedAt($timestamp);
        $this->assertSame($timestamp, $this->forum->getCreatedAt());
    }

    public function testSetAndGetUpdatedAt(): void
    {
        $timestamp = '2025-01-16 14:00:00';
        $this->forum->setUpdatedAt($timestamp);
        $this->assertSame($timestamp, $this->forum->getUpdatedAt());
    }

    public function testSetIdNullAllowed(): void
    {
        $this->forum->setId(null);
        $this->assertNull($this->forum->getId());
    }
}
