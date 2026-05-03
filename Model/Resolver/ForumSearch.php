<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Ronald2Wing\Forum\Api\Data\PostInterface;
use Ronald2Wing\Forum\Api\Data\TopicInterface;
use Ronald2Wing\Forum\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Topic\CollectionFactory as TopicCollectionFactory;

class ForumSearch implements ResolverInterface
{
    public function __construct(
        private readonly PostCollectionFactory $postCollectionFactory,
        private readonly TopicCollectionFactory $topicCollectionFactory,
        private readonly PostTransformer $postTransformer,
        private readonly TopicTransformer $topicTransformer
    ) {}

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        ?array $value = null,
        ?array $args = null
    ): array {
        $input = $args['input'];
        $query = $input['query'];
        $type = $input['type'] ?? 'post';
        $forumId = isset($input['forum_id']) ? (int) $input['forum_id'] : null;
        $pageSize = (int) ($args['pageSize'] ?? 10);
        $currentPage = (int) ($args['currentPage'] ?? 1);

        $result = $type === 'topic'
            ? $this->searchTopics($query, $pageSize, $currentPage, $forumId)
            : $this->searchPosts($query, $pageSize, $currentPage, $forumId);

        $totalCount = $result['total_count'];

        return [
            'items' => $result['items'],
            'total_count' => $totalCount,
            'page_info' => [
                'page_size' => $pageSize,
                'current_page' => $currentPage,
                'total_pages' => $totalCount > 0
                    ? (int) ceil($totalCount / $pageSize)
                    : 1,
            ],
        ];
    }

    private function searchPosts(string $query, int $pageSize, int $currentPage, ?int $forumId): array
    {
        $collection = $this->postCollectionFactory->create();
        $collection->addFieldToFilter('is_deleted', 0);
        $collection->addFieldToFilter('status', PostInterface::STATUS_ENABLED);
        $collection->addFieldToFilter(
            ['content', 'content_original'],
            [
                ['like' => '%' . $query . '%'],
                ['like' => '%' . $query . '%'],
            ]
        );

        if ($forumId !== null) {
            $collection->addFieldToFilter('forum_id', $forumId);
        }

        $collection->setPageSize($pageSize);
        $collection->setCurPage($currentPage);
        $collection->setOrder('created_at', 'DESC');

        $items = [];
        foreach ($collection as $post) {
            $items[] = $this->postTransformer->transform($post);
        }

        return ['items' => $items, 'total_count' => $collection->getSize()];
    }

    private function searchTopics(string $query, int $pageSize, int $currentPage, ?int $forumId): array
    {
        $collection = $this->topicCollectionFactory->create();
        $collection->addFieldToFilter('is_deleted', 0);
        $collection->addFieldToFilter('status', TopicInterface::STATUS_ENABLED);
        $collection->addFieldToFilter(
            ['title', 'description'],
            [
                ['like' => '%' . $query . '%'],
                ['like' => '%' . $query . '%'],
            ]
        );

        if ($forumId !== null) {
            $collection->addFieldToFilter('forum_id', $forumId);
        }

        $collection->setPageSize($pageSize);
        $collection->setCurPage($currentPage);
        $collection->setOrder('created_at', 'DESC');

        $items = [];
        foreach ($collection as $topic) {
            $items[] = $this->topicTransformer->transform($topic);
        }

        return ['items' => $items, 'total_count' => $collection->getSize()];
    }
}
