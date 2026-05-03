<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\Resolver;

use Ronald2Wing\Forum\Api\Data\TopicInterface;

class TopicTransformer
{
    public function transform(TopicInterface $topic): array
    {
        return [
            'post_id' => 0,
            'topic_id' => $topic->getId(),
            'forum_id' => $topic->getForumId(),
            'user_id' => $topic->getUserId(),
            'content' => $topic->getDescription(),
            'status' => $topic->getStatus(),
            'is_sticky' => $topic->getIsSticky(),
            'created_at' => $topic->getCreatedAt(),
            'updated_at' => $topic->getUpdatedAt(),
        ];
    }
}
