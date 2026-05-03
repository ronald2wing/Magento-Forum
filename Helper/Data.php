<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    private const XML_PATH_ENABLED = 'ronald2wing_forum/general/enabled';
    private const XML_PATH_FORUM_ROUTE = 'ronald2wing_forum/general/forum_route';
    private const XML_PATH_FORUM_TITLE = 'ronald2wing_forum/frontend_general/forum_title';
    private const XML_PATH_ENABLE_SEARCH = 'ronald2wing_forum/frontend_general/enable_search';
    private const XML_PATH_ENABLE_BOOKMARKS = 'ronald2wing_forum/frontend_general/enable_bookmarks';
    private const XML_PATH_ENABLE_JUMPTO = 'ronald2wing_forum/frontend_general/enable_jumpto';
    private const XML_PATH_ENABLE_RSS = 'ronald2wing_forum/frontend_general/enable_rss';
    private const XML_PATH_ENABLE_ICONS = 'ronald2wing_forum/frontend_general/enable_icons';
    private const XML_PATH_ENABLE_STATISTIC = 'ronald2wing_forum/frontend_general/enable_statistic';
    private const XML_PATH_ENABLE_WHOISONLINE = 'ronald2wing_forum/frontend_general/enable_whoisonline';
    private const XML_PATH_ALLOW_DELETE_TOPICS = 'ronald2wing_forum/frontend_general/allow_delete_topics';
    private const XML_PATH_TOP_CONTROLS = 'ronald2wing_forum/frontend_general/top_controls';
    private const XML_PATH_FORUM_LAYOUT = 'ronald2wing_forum/frontend_general/forum_page_layout';
    private const XML_PATH_META_TITLE = 'ronald2wing_forum/meta_data/title';
    private const XML_PATH_META_KEYWORDS = 'ronald2wing_forum/meta_data/meta_keywords';
    private const XML_PATH_META_DESCRIPTION = 'ronald2wing_forum/meta_data/meta_description';
    private const XML_PATH_SENDER_EMAIL = 'ronald2wing_forum/notification/sender_email';
    private const XML_PATH_SENDER_NAME = 'ronald2wing_forum/notification/sender_name';
    private const XML_PATH_NOTIFY_CUSTOMER = 'ronald2wing_forum/notification/enable_notify_customer';

    private bool $layoutModified = false;

    public function __construct(
        Context $context,
        private readonly StoreManagerInterface $storeManager,
        private readonly TimezoneInterface $timezone
    ) {
        parent::__construct($context);
    }

    public function isEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLED, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getRoute(?int $storeId = null): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_FORUM_ROUTE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getTitle(?int $storeId = null): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_FORUM_TITLE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isSearchAllowed(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLE_SEARCH, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isBookmarksAllowed(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLE_BOOKMARKS, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isJumpToAllowed(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLE_JUMPTO, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isRssAllowed(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLE_RSS, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isIconsAllowed(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLE_ICONS, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isStatisticAllowed(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLE_STATISTIC, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isWhoIsOnlineAllowed(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLE_WHOISONLINE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isDeleteTopicsAllowed(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ALLOW_DELETE_TOPICS, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isTopControlsAllowed(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_TOP_CONTROLS, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getPageLayout(?int $storeId = null): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_FORUM_LAYOUT, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getMetaTitle(?int $storeId = null): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_META_TITLE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getMetaKeywords(?int $storeId = null): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_META_KEYWORDS, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getMetaDescription(?int $storeId = null): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_META_DESCRIPTION, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getSenderEmail(?int $storeId = null): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_SENDER_EMAIL, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getSenderName(?int $storeId = null): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_SENDER_NAME, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isNotifyCustomerEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_NOTIFY_CUSTOMER, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isLayoutModified(): bool
    {
        return $this->layoutModified;
    }

    public function markLayoutModified(bool $updated): void
    {
        $this->layoutModified = $updated;
    }

    public function formatDateTime(string $dateTime): string
    {
        return $this->timezone->date(new \DateTime($dateTime))->format('m/d/Y H:i:s');
    }
}
