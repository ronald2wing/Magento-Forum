<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Model\Icon;

class IconTest extends TestCase
{
    private Icon $icon;

    protected function setUp(): void
    {
        $this->icon = $this->getMockBuilder(Icon::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_init', '_construct'])
            ->getMock();
    }

    public function testGetIconListHasExpectedIcons(): void
    {
        $list = $this->icon->getIconList();
        $this->assertIsArray($list);
        $this->assertArrayHasKey('smile', $list);
        $this->assertArrayHasKey('wink', $list);
        $this->assertArrayHasKey('sad', $list);
        $this->assertArrayHasKey('accept', $list);
        $this->assertArrayHasKey('cancel', $list);
    }

    public function testGetIconListCount(): void
    {
        $list = $this->icon->getIconList();
        $this->assertCount(16, $list);
    }

    public function testGetIconIds(): void
    {
        $ids = $this->icon->getIconIds();
        $this->assertIsArray($ids);
        $this->assertContains('smile', $ids);
        $this->assertContains('cancel', $ids);
    }

    public function testGetIconLabel(): void
    {
        $this->assertSame('Smile', $this->icon->getIconLabel('smile'));
        $this->assertSame('Cry', $this->icon->getIconLabel('cry'));
    }

    public function testGetIconLabelUnknown(): void
    {
        $this->assertSame('', $this->icon->getIconLabel('nonexistent'));
    }
}
