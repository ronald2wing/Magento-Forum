<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Api\Data\PostInterface;
use Ronald2Wing\Forum\Model\Post;

class PostTest extends TestCase
{
    private Post $post;

    protected function setUp(): void
    {
        $this->post = $this->getMockBuilder(Post::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_init', '_construct'])
            ->getMock();
    }

    public function testGetIdReturnsNullWhenNoData(): void
    {
        $this->assertNull($this->post->getId());
    }

    public function testSetAndGetId(): void
    {
        $this->post->setId(100);
        $this->assertSame(100, $this->post->getId());
    }

    public function testSetAndGetTopicId(): void
    {
        $this->post->setTopicId(15);
        $this->assertSame(15, $this->post->getTopicId());
    }

    public function testSetAndGetForumId(): void
    {
        $this->post->setForumId(5);
        $this->assertSame(5, $this->post->getForumId());
    }

    public function testSetAndGetUserId(): void
    {
        $this->post->setUserId(7);
        $this->assertSame(7, $this->post->getUserId());
    }

    public function testUserIdDefaultNull(): void
    {
        $this->assertNull($this->post->getUserId());
    }

    public function testSetAndGetContent(): void
    {
        $this->post->setContent('This is a post content');
        $this->assertSame('This is a post content', $this->post->getContent());
    }

    public function testContentDefaultNull(): void
    {
        $this->assertNull($this->post->getContent());
    }

    public function testSetAndGetContentOriginal(): void
    {
        $this->post->setContentOriginal('<p>Original post</p>');
        $this->assertSame('<p>Original post</p>', $this->post->getContentOriginal());
    }

    public function testStatusDefaultsToDisabled(): void
    {
        $this->assertSame(0, $this->post->getStatus());
    }

    public function testSetStatusToEnabled(): void
    {
        $this->post->setStatus(PostInterface::STATUS_ENABLED);
        $this->assertSame(1, $this->post->getStatus());
    }

    public function testSetStatusToDisabled(): void
    {
        $this->post->setStatus(PostInterface::STATUS_DISABLED);
        $this->assertSame(0, $this->post->getStatus());
    }

    public function testIsStickyDefaultsToFalse(): void
    {
        $this->assertFalse($this->post->getIsSticky());
    }

    public function testSetAndGetIsSticky(): void
    {
        $this->post->setIsSticky(true);
        $this->assertTrue($this->post->getIsSticky());
    }

    public function testIsDeletedDefaultsToFalse(): void
    {
        $this->assertFalse($this->post->getIsDeleted());
    }

    public function testSetAndGetIsDeleted(): void
    {
        $this->post->setIsDeleted(true);
        $this->assertTrue($this->post->getIsDeleted());
    }

    public function testSetAndGetCreatedAt(): void
    {
        $timestamp = '2025-03-01 09:00:00';
        $this->post->setCreatedAt($timestamp);
        $this->assertSame($timestamp, $this->post->getCreatedAt());
    }

    public function testSetAndGetUpdatedAt(): void
    {
        $timestamp = '2025-03-02 18:00:00';
        $this->post->setUpdatedAt($timestamp);
        $this->assertSame($timestamp, $this->post->getUpdatedAt());
    }

    public function testGetIdentities(): void
    {
        $this->post->setId(50);
        $this->assertSame(['ronald2wing_forum_p_50'], $this->post->getIdentities());
    }

    public function testGetIdentitiesReturnsNullTagWhenNoId(): void
    {
        $this->assertSame(['ronald2wing_forum_p_'], $this->post->getIdentities());
    }

    public function testSetAndGetProductId(): void
    {
        $this->post->setProductId(200);
        $this->assertSame(200, $this->post->getProductId());
    }
}
