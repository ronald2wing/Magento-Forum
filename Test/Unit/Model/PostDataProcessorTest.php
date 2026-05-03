<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Controller\Topic\PostDataProcessor as TopicPostDataProcessor;
use Ronald2Wing\Forum\Controller\Customer\PostDataProcessor as CustomerPostDataProcessor;
use Ronald2Wing\Forum\Controller\Adminhtml\Topic\PostDataProcessor as AdminhtmlTopicPostDataProcessor;

class PostDataProcessorTest extends TestCase
{
    public function testTopicFilterStripsScriptTags(): void
    {
        $processor = new TopicPostDataProcessor();
        $input = ['title' => 'Test', 'post' => '<script>alert("xss")</script>Hello'];
        $result = $processor->filter($input);
        $this->assertStringNotContainsString('<script>', $result['post']);
        $this->assertStringContainsString('Hello', $result['post']);
    }

    public function testTopicFilterStripsJavascriptProtocol(): void
    {
        $processor = new TopicPostDataProcessor();
        $input = ['title' => 'Test', 'post' => '<a href="javascript:alert(1)">click</a>'];
        $result = $processor->filter($input);
        $this->assertStringNotContainsString('javascript:', $result['post']);
    }

    public function testTopicFilterStripsEventHandlers(): void
    {
        $processor = new TopicPostDataProcessor();
        $input = ['title' => 'Test', 'post' => '<img src=x onmouseover="alert(1)">'];
        $result = $processor->filter($input);
        $this->assertStringNotContainsString('onmouseover', $result['post']);
    }

    public function testTopicFilterStripsOnclick(): void
    {
        $processor = new TopicPostDataProcessor();
        $input = ['title' => 'Test', 'post' => '<div onclick="alert(1)">click</div>'];
        $result = $processor->filter($input);
        $this->assertStringNotContainsString('onclick', $result['post']);
    }

    public function testTopicFilterStripsOnmouseover(): void
    {
        $processor = new TopicPostDataProcessor();
        $input = ['title' => 'Test', 'post' => '<span onmouseover="bad()">hover</span>'];
        $result = $processor->filter($input);
        $this->assertStringNotContainsString('onmouseover', $result['post']);
    }

    public function testTopicFilterStripsTagsFromTitle(): void
    {
        $processor = new TopicPostDataProcessor();
        $input = ['title' => '<b>My Topic</b>', 'post' => 'Content'];
        $result = $processor->filter($input);
        $this->assertSame('My Topic', $result['title']);
    }

    public function testTopicFilterPreservesBoldTagsInPost(): void
    {
        $processor = new TopicPostDataProcessor();
        $input = ['title' => 'Safe', 'post' => '<p><b>Bold</b> text</p>'];
        $result = $processor->filter($input);
        $this->assertStringContainsString('<b>Bold</b>', $result['post']);
    }

    public function testTopicFilterHandlesEmptyInput(): void
    {
        $processor = new TopicPostDataProcessor();
        $input = [];
        $result = $processor->filter($input);
        $this->assertSame('-TITLE-NOT-ADDED', $result['title']);
    }

    public function testTopicFilterDefaultsEmptyTitle(): void
    {
        $processor = new TopicPostDataProcessor();
        $input = ['title' => '', 'post' => 'Content'];
        $result = $processor->filter($input);
        $this->assertSame('-TITLE-NOT-ADDED', $result['title']);
    }

    public function testTopicFilterKeepsWhitespaceTitleAsNonEmpty(): void
    {
        $processor = new TopicPostDataProcessor();
        $input = ['title' => '   ', 'post' => 'Content'];
        $result = $processor->filter($input);
        $this->assertSame('   ', $result['title']);
    }

    public function testTopicFilterStripsTagsFromDescription(): void
    {
        $processor = new TopicPostDataProcessor();
        $input = ['title' => 'Test', 'description' => '<script>evil</script>Desc', 'post' => 'Content'];
        $result = $processor->filter($input);
        $this->assertSame('evilDesc', $result['description']);
    }

    public function testCustomerFilterStripsTagsFromNickname(): void
    {
        $processor = new CustomerPostDataProcessor();
        $input = ['nickname' => '<b>Bob</b>'];
        $result = $processor->filter($input);
        $this->assertSame('Bob', $result['nickname']);
    }

    public function testCustomerFilterPreservesAllowedTagsInSignature(): void
    {
        $processor = new CustomerPostDataProcessor();
        $input = ['signature' => '<b>Bold</b> and <i>italic</i>'];
        $result = $processor->filter($input);
        $this->assertStringContainsString('<b>Bold</b>', $result['signature']);
        $this->assertStringContainsString('<i>italic</i>', $result['signature']);
    }

    public function testCustomerFilterStripsUnsafeTagsFromSignature(): void
    {
        $processor = new CustomerPostDataProcessor();
        $input = ['signature' => '<script>alert(1)</script><b>Safe</b>'];
        $result = $processor->filter($input);
        $this->assertStringNotContainsString('<script>', $result['signature']);
        $this->assertStringContainsString('<b>Safe</b>', $result['signature']);
    }

    public function testCustomerFilterHandlesEmptyInput(): void
    {
        $processor = new CustomerPostDataProcessor();
        $input = [];
        $result = $processor->filter($input);
        $this->assertSame([], $result);
    }

    public function testAdminhtmlTopicFilterStripsTagsFromTitle(): void
    {
        $processor = new AdminhtmlTopicPostDataProcessor();
        $input = ['title' => '<h1>Admin Title</h1>'];
        $result = $processor->filter($input);
        $this->assertSame('Admin Title', $result['title']);
    }

    public function testAdminhtmlTopicFilterExtractsIconFromArray(): void
    {
        $processor = new AdminhtmlTopicPostDataProcessor();
        $input = ['icon_id' => ['star']];
        $result = $processor->filter($input);
        $this->assertSame('star', $result['icon_id']);
    }

    public function testAdminhtmlTopicFilterSetsEmptyIconForEmptyArray(): void
    {
        $processor = new AdminhtmlTopicPostDataProcessor();
        $input = ['icon_id' => []];
        $result = $processor->filter($input);
        $this->assertSame('', $result['icon_id']);
    }

    public function testAdminhtmlTopicFilterSetsEmptyIconWhenMissing(): void
    {
        $processor = new AdminhtmlTopicPostDataProcessor();
        $input = [];
        $result = $processor->filter($input);
        $this->assertSame('', $result['icon_id']);
    }

    public function testAdminhtmlTopicValidateReturnsTrue(): void
    {
        $processor = new AdminhtmlTopicPostDataProcessor();
        $this->assertTrue($processor->validate([]));
    }
}
