<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Helper\Data;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\ScopeInterface;

class DataTest extends TestCase
{
    private Data $helper;
    private ScopeConfigInterface $scopeConfig;

    protected function setUp(): void
    {
        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $context = $this->createMock(Context::class);
        $context->method('getScopeConfig')->willReturn($this->scopeConfig);

        $storeManager = $this->createMock(StoreManagerInterface::class);
        $timezone = $this->createMock(TimezoneInterface::class);

        $this->helper = new Data($context, $storeManager, $timezone);
    }

    public function testIsEnabledReturnsTrue(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('isSetFlag')
            ->with('ronald2wing_forum/general/enabled', ScopeInterface::SCOPE_STORE, null)
            ->willReturn(true);
        $this->assertTrue($this->helper->isEnabled());
    }

    public function testIsEnabledReturnsFalse(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('isSetFlag')
            ->with('ronald2wing_forum/general/enabled', ScopeInterface::SCOPE_STORE, null)
            ->willReturn(false);
        $this->assertFalse($this->helper->isEnabled());
    }

    public function testGetTitle(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with('ronald2wing_forum/frontend_general/forum_title', ScopeInterface::SCOPE_STORE, null)
            ->willReturn('My Forum');
        $this->assertSame('My Forum', $this->helper->getTitle());
    }

    public function testGetRoute(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with('ronald2wing_forum/general/forum_route', ScopeInterface::SCOPE_STORE, null)
            ->willReturn('community');
        $this->assertSame('community', $this->helper->getRoute());
    }

    public function testIsSearchAllowedReturnsTrue(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('isSetFlag')
            ->with('ronald2wing_forum/frontend_general/enable_search', ScopeInterface::SCOPE_STORE, null)
            ->willReturn(true);
        $this->assertTrue($this->helper->isSearchAllowed());
    }

    public function testIsBookmarksAllowedReturnsFalse(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('isSetFlag')
            ->with('ronald2wing_forum/frontend_general/enable_bookmarks', ScopeInterface::SCOPE_STORE, null)
            ->willReturn(false);
        $this->assertFalse($this->helper->isBookmarksAllowed());
    }

    public function testIsJumpToAllowed(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('isSetFlag')
            ->with('ronald2wing_forum/frontend_general/enable_jumpto', ScopeInterface::SCOPE_STORE, null)
            ->willReturn(true);
        $this->assertTrue($this->helper->isJumpToAllowed());
    }

    public function testIsRssAllowed(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('isSetFlag')
            ->with('ronald2wing_forum/frontend_general/enable_rss', ScopeInterface::SCOPE_STORE, null)
            ->willReturn(true);
        $this->assertTrue($this->helper->isRssAllowed());
    }

    public function testIsIconsAllowed(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('isSetFlag')
            ->with('ronald2wing_forum/frontend_general/enable_icons', ScopeInterface::SCOPE_STORE, null)
            ->willReturn(false);
        $this->assertFalse($this->helper->isIconsAllowed());
    }

    public function testIsStatisticAllowed(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('isSetFlag')
            ->with('ronald2wing_forum/frontend_general/enable_statistic', ScopeInterface::SCOPE_STORE, null)
            ->willReturn(true);
        $this->assertTrue($this->helper->isStatisticAllowed());
    }

    public function testIsWhoIsOnlineAllowed(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('isSetFlag')
            ->with('ronald2wing_forum/frontend_general/enable_whoisonline', ScopeInterface::SCOPE_STORE, null)
            ->willReturn(false);
        $this->assertFalse($this->helper->isWhoIsOnlineAllowed());
    }

    public function testIsDeleteTopicsAllowed(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('isSetFlag')
            ->with('ronald2wing_forum/frontend_general/allow_delete_topics', ScopeInterface::SCOPE_STORE, null)
            ->willReturn(true);
        $this->assertTrue($this->helper->isDeleteTopicsAllowed());
    }

    public function testIsTopControlsAllowed(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('isSetFlag')
            ->with('ronald2wing_forum/frontend_general/top_controls', ScopeInterface::SCOPE_STORE, null)
            ->willReturn(false);
        $this->assertFalse($this->helper->isTopControlsAllowed());
    }

    public function testGetMetaTitle(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with('ronald2wing_forum/meta_data/title', ScopeInterface::SCOPE_STORE, null)
            ->willReturn('SEO Title');
        $this->assertSame('SEO Title', $this->helper->getMetaTitle());
    }

    public function testGetMetaKeywords(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with('ronald2wing_forum/meta_data/meta_keywords', ScopeInterface::SCOPE_STORE, null)
            ->willReturn('a,b,c');
        $this->assertSame('a,b,c', $this->helper->getMetaKeywords());
    }

    public function testGetMetaDescription(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with('ronald2wing_forum/meta_data/meta_description', ScopeInterface::SCOPE_STORE, null)
            ->willReturn('desc');
        $this->assertSame('desc', $this->helper->getMetaDescription());
    }

    public function testGetSenderEmail(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with('ronald2wing_forum/notification/sender_email', ScopeInterface::SCOPE_STORE, null)
            ->willReturn('noreply@example.com');
        $this->assertSame('noreply@example.com', $this->helper->getSenderEmail());
    }

    public function testGetSenderName(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with('ronald2wing_forum/notification/sender_name', ScopeInterface::SCOPE_STORE, null)
            ->willReturn('Admin');
        $this->assertSame('Admin', $this->helper->getSenderName());
    }

    public function testIsNotifyCustomerEnabled(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('isSetFlag')
            ->with('ronald2wing_forum/notification/enable_notify_customer', ScopeInterface::SCOPE_STORE, null)
            ->willReturn(true);
        $this->assertTrue($this->helper->isNotifyCustomerEnabled());
    }

    public function testIsLayoutModifiedInitiallyFalse(): void
    {
        $this->assertFalse($this->helper->isLayoutModified());
    }

    public function testMarkLayoutModified(): void
    {
        $this->helper->markLayoutModified(true);
        $this->assertTrue($this->helper->isLayoutModified());

        $this->helper->markLayoutModified(false);
        $this->assertFalse($this->helper->isLayoutModified());
    }

    public function testGetPageLayoutDefault(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with('ronald2wing_forum/frontend_general/forum_page_layout', ScopeInterface::SCOPE_STORE, null)
            ->willReturn('1column');
        $this->assertSame('1column', $this->helper->getPageLayout());
    }
}
