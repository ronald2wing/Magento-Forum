<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\Resolver;

use Ronald2Wing\Forum\Api\Data\PostInterface;

class PostTransformer
{
    public function transform(PostInterface $post): array
    {
        return [
            'post_id' => $post->getId(),
            'topic_id' => $post->getTopicId(),
            'forum_id' => $post->getForumId(),
            'user_id' => $post->getUserId(),
            'content' => $post->getContent(),
            'status' => $post->getStatus(),
            'is_sticky' => $post->getIsSticky(),
            'created_at' => $post->getCreatedAt(),
            'updated_at' => $post->getUpdatedAt(),
        ];
    }
}
