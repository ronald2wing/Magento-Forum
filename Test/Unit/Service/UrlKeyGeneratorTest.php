<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Model\Service\UrlKeyGenerator;
use Ronald2Wing\Forum\Model\ResourceModel\Forum\CollectionFactory as ForumCollectionFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Topic\CollectionFactory as TopicCollectionFactory;

class UrlKeyGeneratorTest extends TestCase
{
    private UrlKeyGenerator $generator;

    protected function setUp(): void
    {
        $this->generator = new UrlKeyGenerator(
            new TopicCollectionFactory(),
            new ForumCollectionFactory()
        );
    }

    public function testGenerateSimpleTitle(): void
    {
        $result = $this->generator->generate('Hello World');
        $this->assertSame('hello-world', $result);
    }

    public function testGenerateWithSpecialChars(): void
    {
        $result = $this->generator->generate('Hello! World? (Test)');
        $this->assertSame('hello-world-test', $result);
    }

    public function testGenerateMultipleSpaces(): void
    {
        $result = $this->generator->generate('  Hello   World  ');
        $this->assertSame('hello-world', $result);
    }

    public function testGenerateMultipleHyphens(): void
    {
        $result = $this->generator->generate('Hello---World');
        $this->assertSame('hello-world', $result);
    }

    public function testGenerateSlash(): void
    {
        $result = $this->generator->generate('Hello/World');
        $this->assertSame('hello-world', $result);
    }

    public function testGenerateNumbers(): void
    {
        $result = $this->generator->generate('Forum 2025');
        $this->assertSame('forum-2025', $result);
    }

    public function testGenerateEmptyTitle(): void
    {
        $result = $this->generator->generate('');
        $this->assertStringStartsWith('item-', $result);
    }

    public function testGenerateWhitespaceOnlyTitle(): void
    {
        $result = $this->generator->generate('   ');
        $this->assertStringStartsWith('item-', $result);
    }

    public function testGenerateUppercaseConversion(): void
    {
        $result = $this->generator->generate('HELLO WORLD');
        $this->assertSame('hello-world', $result);
    }

    public function testGenerateMixedCase(): void
    {
        $result = $this->generator->generate('HelloWorld');
        $this->assertSame('helloworld', $result);
    }

    public function testGenerateLeadingAndTrailingHyphensRemoved(): void
    {
        $result = $this->generator->generate('!!!Hello World!!!');
        $this->assertSame('hello-world', $result);
    }

    public function testGenerateOnlySpecialChars(): void
    {
        $result = $this->generator->generate('!@#$%');
        $this->assertStringStartsWith('item-', $result);
    }
}
