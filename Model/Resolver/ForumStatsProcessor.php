<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Ronald2Wing\Forum\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use Ronald2Wing\Forum\Api\Data\PostInterface;

class ForumStatsProcessor implements ResolverInterface
{
    public function __construct(
        private readonly PostCollectionFactory $postCollectionFactory
    ) {}

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ): ?array {
        $forumId = $value['forum_id'] ?? null;
        if ($forumId === null) {
            return null;
        }

        $postCollection = $this->postCollectionFactory->create();
        $postCollection->addFieldToFilter('forum_id', $forumId);
        $postCollection->addFieldToFilter('is_deleted', 0);
        $postCollection->addFieldToFilter('status', PostInterface::STATUS_ENABLED);
        $postCollection->setOrder('created_at', 'DESC');
        $postCollection->setPageSize(1);

        $lastPost = $postCollection->getFirstItem();
        if (!$lastPost->getId()) {
            return null;
        }

        return [
            'post_id' => (int) $lastPost->getId(),
            'topic_id' => (int) $lastPost->getTopicId(),
            'forum_id' => $forumId,
            'user_id' => (int) $lastPost->getUserId(),
            'content' => $lastPost->getContent(),
            'status' => (int) $lastPost->getStatus(),
            'is_sticky' => $lastPost->getIsSticky() ? 1 : 0,
            'created_at' => $lastPost->getCreatedAt(),
            'updated_at' => $lastPost->getUpdatedAt(),
        ];
    }
}
