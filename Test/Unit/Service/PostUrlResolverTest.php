<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Model\Service\PostUrlResolver;

class PostUrlResolverTest extends TestCase
{
    private PostUrlResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver = $this->getMockBuilder(PostUrlResolver::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();
    }

    public function testClassExists(): void
    {
        $this->assertTrue(class_exists(PostUrlResolver::class));
    }
}
