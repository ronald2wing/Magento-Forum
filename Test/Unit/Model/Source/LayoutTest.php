<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Model\Source;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Model\Source\Layout;

class LayoutTest extends TestCase
{
    public function testToOptionArray(): void
    {
        $source = new Layout();
        $result = $source->toOptionArray();

        $this->assertIsArray($result);
        $this->assertCount(3, $result);
        $this->assertSame('1column', $result[0]['value']);
        $this->assertSame('2columns-left', $result[1]['value']);
        $this->assertSame('2columns-right', $result[2]['value']);
    }
}
