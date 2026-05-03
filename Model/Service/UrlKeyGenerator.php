<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\Service;

use Ronald2Wing\Forum\Model\ResourceModel\Topic\CollectionFactory as TopicCollectionFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Forum\CollectionFactory as ForumCollectionFactory;

class UrlKeyGenerator
{
    public function __construct(
        private readonly TopicCollectionFactory $topicCollectionFactory,
        private readonly ForumCollectionFactory $forumCollectionFactory
    ) {}

    public function generate(string $title): string
    {
        $urlKey = strtolower(trim($title));
        $urlKey = preg_replace('/[^a-z0-9-]+/', '-', $urlKey) ?? $urlKey;
        $urlKey = trim($urlKey, '-');
        $urlKey = preg_replace('/-+/', '-', $urlKey) ?? $urlKey;

        return $urlKey ?: 'item-' . time();
    }

    public function buildUniqueForumUrlKey(string $title, ?int $excludeId = null): string
    {
        $baseKey = $this->generate($title);

        return $this->makeUniqueForum($baseKey, $excludeId);
    }

    public function buildUniqueTopicUrlKey(string $title, int $forumId, ?int $excludeId = null): string
    {
        $baseKey = $this->generate($title);

        return $this->makeUniqueTopic($baseKey, $forumId, $excludeId);
    }

    private function makeUniqueForum(string $key, ?int $excludeId): string
    {
        $collection = $this->forumCollectionFactory->create();
        $collection->addFieldToFilter('url_key', $key);

        if ($excludeId) {
            $collection->addFieldToFilter('forum_id', ['neq' => $excludeId]);
        }

        if ($collection->getSize() > 0) {
            $key .= '-' . time();
        }

        return $key;
    }

    private function makeUniqueTopic(string $key, int $forumId, ?int $excludeId): string
    {
        $collection = $this->topicCollectionFactory->create();
        $collection->addFieldToFilter('url_key', $key);
        $collection->addFieldToFilter('forum_id', $forumId);

        if ($excludeId) {
            $collection->addFieldToFilter('topic_id', ['neq' => $excludeId]);
        }

        if ($collection->getSize() > 0) {
            $key .= '-' . time();
        }

        return $key;
    }
}
