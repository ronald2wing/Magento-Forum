<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Helper;

use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class Url
{
    public function __construct(
        private readonly StoreManagerInterface $storeManager,
        private readonly Data $dataHelper
    ) {}

    public function getForumUrl(?string $urlKey = null): string
    {
        return $this->buildUrl($urlKey);
    }

    public function getTopicUrl(string $forumUrlKey, string $topicUrlKey): string
    {
        return $this->buildUrl($forumUrlKey . '/' . $topicUrlKey);
    }

    public function getAddTopicUrl(int $forumId): string
    {
        return $this->getBaseUrl() . $this->getRoute() . '/topic/new/id/' . $forumId . '/';
    }

    public function getEditTopicUrl(int $topicId): string
    {
        return $this->getBaseUrl() . $this->getRoute() . '/topic/edit/topic_id/' . $topicId . '/';
    }

    public function getDeleteTopicUrl(int $topicId): string
    {
        return $this->getBaseUrl() . $this->getRoute() . '/topic/delete/topic_id/' . $topicId . '/';
    }

    public function getDeletePostUrl(int $postId): string
    {
        return $this->getBaseUrl() . $this->getRoute() . '/post/delete/post_id/' . $postId . '/';
    }

    public function getUnsubscribeUrl(string $hash): string
    {
        return $this->getBaseUrl() . $this->getRoute() . '/notify/remove/hash/' . $hash . '/';
    }

    public function getCustomerAccountUrl(): string
    {
        return $this->getBaseUrl() . $this->getRoute() . '/customer/index/';
    }

    public function getCustomerPostsUrl(): string
    {
        return $this->getBaseUrl() . $this->getRoute() . '/customer/posts/';
    }

    public function getCustomerTopicsUrl(): string
    {
        return $this->getBaseUrl() . $this->getRoute() . '/customer/topics/';
    }

    public function getSearchUrl(): string
    {
        return $this->getBaseUrl() . $this->getRoute() . '/search/index/';
    }

    public function getBookmarkUrl(): string
    {
        return $this->getBaseUrl() . $this->getRoute() . '/bookmark/index/';
    }

    public function getRssUrl(): string
    {
        return $this->getBaseUrl() . $this->getRoute() . '/rss/index/';
    }

    private function buildUrl(?string $path = null): string
    {
        $url = $this->getBaseUrl() . $this->getRoute() . '/';
        if ($path) {
            $url .= $path . '/';
        }
        return $url;
    }

    private function getBaseUrl(): string
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_LINK);
    }

    private function getRoute(): string
    {
        return $this->dataHelper->getRoute();
    }
}
