<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Helper\Url;
use Ronald2Wing\Forum\Helper\Data;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\Store;
use Magento\Framework\UrlInterface;

class UrlTest extends TestCase
{
    private Url $helper;
    private Data $dataHelper;

    protected function setUp(): void
    {
        $store = $this->createMock(Store::class);
        $store->method('getBaseUrl')
            ->with(UrlInterface::URL_TYPE_LINK)
            ->willReturn('https://example.com/');

        $storeManager = $this->createMock(StoreManagerInterface::class);
        $storeManager->method('getStore')->willReturn($store);

        $this->dataHelper = $this->createMock(Data::class);
        $this->dataHelper->method('getRoute')->willReturn('forum');

        $this->helper = new Url($storeManager, $this->dataHelper);
    }

    public function testGetForumUrl(): void
    {
        $url = $this->helper->getForumUrl();
        $this->assertSame('https://example.com/forum/', $url);
    }

    public function testGetForumUrlWithPath(): void
    {
        $url = $this->helper->getForumUrl('my-forum');
        $this->assertSame('https://example.com/forum/my-forum/', $url);
    }

    public function testGetForumUrlWithNullPath(): void
    {
        $url = $this->helper->getForumUrl(null);
        $this->assertSame('https://example.com/forum/', $url);
    }

    public function testGetTopicUrl(): void
    {
        $url = $this->helper->getTopicUrl('my-forum', 'my-topic');
        $this->assertSame('https://example.com/forum/my-forum/my-topic/', $url);
    }

    public function testGetAddTopicUrl(): void
    {
        $url = $this->helper->getAddTopicUrl(5);
        $this->assertSame('https://example.com/forum/topic/new/id/5/', $url);
    }

    public function testGetEditTopicUrl(): void
    {
        $url = $this->helper->getEditTopicUrl(42);
        $this->assertSame('https://example.com/forum/topic/edit/topic_id/42/', $url);
    }

    public function testGetDeleteTopicUrl(): void
    {
        $url = $this->helper->getDeleteTopicUrl(42);
        $this->assertSame('https://example.com/forum/topic/delete/topic_id/42/', $url);
    }

    public function testGetDeletePostUrl(): void
    {
        $url = $this->helper->getDeletePostUrl(100);
        $this->assertSame('https://example.com/forum/post/delete/post_id/100/', $url);
    }

    public function testGetUnsubscribeUrl(): void
    {
        $url = $this->helper->getUnsubscribeUrl('abc123hash');
        $this->assertSame('https://example.com/forum/notify/remove/hash/abc123hash/', $url);
    }

    public function testGetCustomerAccountUrl(): void
    {
        $url = $this->helper->getCustomerAccountUrl();
        $this->assertSame('https://example.com/forum/customer/index/', $url);
    }

    public function testGetCustomerPostsUrl(): void
    {
        $url = $this->helper->getCustomerPostsUrl();
        $this->assertSame('https://example.com/forum/customer/posts/', $url);
    }

    public function testGetCustomerTopicsUrl(): void
    {
        $url = $this->helper->getCustomerTopicsUrl();
        $this->assertSame('https://example.com/forum/customer/topics/', $url);
    }

    public function testGetSearchUrl(): void
    {
        $url = $this->helper->getSearchUrl();
        $this->assertSame('https://example.com/forum/search/index/', $url);
    }

    public function testGetBookmarkUrl(): void
    {
        $url = $this->helper->getBookmarkUrl();
        $this->assertSame('https://example.com/forum/bookmark/index/', $url);
    }

    public function testGetRssUrl(): void
    {
        $url = $this->helper->getRssUrl();
        $this->assertSame('https://example.com/forum/rss/index/', $url);
    }
}
