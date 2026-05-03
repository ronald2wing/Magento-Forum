<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Ronald2Wing\Forum\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use Ronald2Wing\Forum\Api\Data\PostInterface;

class TopicStatsProcessor implements ResolverInterface
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
        $topicId = $value['topic_id'] ?? null;
        if ($topicId === null) {
            return null;
        }

        $postCollection = $this->postCollectionFactory->create();
        $postCollection->addFieldToFilter('topic_id', $topicId);
        $postCollection->addFieldToFilter('is_deleted', 0);
        $postCollection->addFieldToFilter('status', PostInterface::STATUS_ENABLED);
        $postCollection->setOrder('created_at', $field->getName() === 'first_post' ? 'ASC' : 'DESC');
        $postCollection->setPageSize(1);

        $post = $postCollection->getFirstItem();
        if (!$post->getId()) {
            return null;
        }

        return [
            'post_id' => (int) $post->getId(),
            'topic_id' => $topicId,
            'forum_id' => (int) $post->getForumId(),
            'user_id' => (int) $post->getUserId(),
            'content' => $post->getContent(),
            'status' => (int) $post->getStatus(),
            'is_sticky' => $post->getIsSticky() ? 1 : 0,
            'created_at' => $post->getCreatedAt(),
            'updated_at' => $post->getUpdatedAt(),
        ];
    }
}
