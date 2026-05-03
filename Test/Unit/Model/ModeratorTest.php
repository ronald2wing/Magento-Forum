<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Model\Moderator;

class ModeratorTest extends TestCase
{
    private Moderator $moderator;

    protected function setUp(): void
    {
        $this->moderator = $this->getMockBuilder(Moderator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_init', '_construct'])
            ->getMock();
    }

    public function testGetIdReturnsNullWhenNoData(): void
    {
        $this->assertNull($this->moderator->getId());
    }

    public function testSetAndGetId(): void
    {
        $this->moderator->setId(10);
        $this->assertSame(10, $this->moderator->getId());
    }

    public function testSetAndGetUserId(): void
    {
        $this->moderator->setUserId(25);
        $this->assertSame(25, $this->moderator->getUserId());
    }

    public function testUserIdDefaultNull(): void
    {
        $this->assertNull($this->moderator->getUserId());
    }

    public function testSetAndGetWebsiteId(): void
    {
        $this->moderator->setWebsiteId(1);
        $this->assertSame(1, $this->moderator->getWebsiteId());
    }

    public function testWebsiteIdDefaultNull(): void
    {
        $this->assertNull($this->moderator->getWebsiteId());
    }

    public function testSetAndGetUserWebsiteId(): void
    {
        $this->moderator->setUserWebsiteId(2);
        $this->assertSame(2, $this->moderator->getUserWebsiteId());
    }

    public function testUserWebsiteIdDefaultNull(): void
    {
        $this->assertNull($this->moderator->getUserWebsiteId());
    }
}
