<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Helper\Dates;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class DatesTest extends TestCase
{
    public function testFormatDateTimeDefaultFormat(): void
    {
        $timezone = $this->createMock(TimezoneInterface::class);
        $timezone->method('date')->willReturn(new \DateTime('2025-01-15 10:30:00'));

        $helper = new Dates($timezone);
        $result = $helper->formatDateTime('2025-01-15 10:30:00');

        $this->assertSame('01/15/2025 10:30:00', $result);
    }

    public function testFormatDateTimeCustomFormat(): void
    {
        $timezone = $this->createMock(TimezoneInterface::class);
        $timezone->method('date')->willReturn(new \DateTime('2025-01-15'));

        $helper = new Dates($timezone);
        $result = $helper->formatDateTime('2025-01-15', 'Y-m-d');

        $this->assertSame('2025-01-15', $result);
    }

    public function testFormatDateTimeWithDifferentFormat(): void
    {
        $timezone = $this->createMock(TimezoneInterface::class);
        $timezone->method('date')->willReturn(new \DateTime('2025-06-20 14:45:00'));

        $helper = new Dates($timezone);
        $result = $helper->formatDateTime('2025-06-20 14:45:00', 'H:i');

        $this->assertSame('14:45', $result);
    }
}
