<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Model\UserSettings;

class UserSettingsTest extends TestCase
{
    private UserSettings $userSettings;

    protected function setUp(): void
    {
        $this->userSettings = $this->getMockBuilder(UserSettings::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_init', '_construct'])
            ->getMock();
    }

    public function testGetIdReturnsNullWhenNoData(): void
    {
        $this->assertNull($this->userSettings->getId());
    }

    public function testSetAndGetId(): void
    {
        $this->userSettings->setId(33);
        $this->assertSame(33, $this->userSettings->getId());
    }

    public function testSetAndGetUserId(): void
    {
        $this->userSettings->setUserId(50);
        $this->assertSame(50, $this->userSettings->getUserId());
    }

    public function testUserIdDefaultNull(): void
    {
        $this->assertNull($this->userSettings->getUserId());
    }

    public function testIdAndUserIdAreTheSameDataKey(): void
    {
        $this->userSettings->setId(10);
        $this->assertSame(10, $this->userSettings->getUserId());
    }
}
