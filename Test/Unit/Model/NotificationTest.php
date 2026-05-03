<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Model\Notification;

class NotificationTest extends TestCase
{
    private Notification $notification;

    protected function setUp(): void
    {
        $this->notification = $this->getMockBuilder(Notification::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_init', '_construct'])
            ->getMock();
    }

    public function testGetIdReturnsNullWhenNoData(): void
    {
        $this->assertNull($this->notification->getId());
    }

    public function testSetAndGetId(): void
    {
        $this->notification->setId(42);
        $this->assertSame(42, $this->notification->getId());
    }

    public function testSetAndGetUserId(): void
    {
        $this->notification->setUserId(15);
        $this->assertSame(15, $this->notification->getUserId());
    }

    public function testUserIdDefaultNull(): void
    {
        $this->assertNull($this->notification->getUserId());
    }

    public function testSetAndGetTopicId(): void
    {
        $this->notification->setTopicId(77);
        $this->assertSame(77, $this->notification->getTopicId());
    }

    public function testTopicIdDefaultNull(): void
    {
        $this->assertNull($this->notification->getTopicId());
    }

    public function testSetAndGetUnsubscribeHash(): void
    {
        $this->notification->setUnsubscribeHash('abc123def456');
        $this->assertSame('abc123def456', $this->notification->getUnsubscribeHash());
    }

    public function testUnsubscribeHashDefaultNull(): void
    {
        $this->assertNull($this->notification->getUnsubscribeHash());
    }
}
