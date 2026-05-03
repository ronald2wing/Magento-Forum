<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Ronald2Wing\Forum\Api\TopicRepositoryInterface;
use Ronald2Wing\Forum\Api\Data\TopicInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;

class ForumTopics implements ResolverInterface
{
    public function __construct(
        private readonly TopicRepositoryInterface $topicRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly SortOrderBuilder $sortOrderBuilder
    ) {}

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        ?array $value = null,
        ?array $args = null
    ): array {
        $forumId = (int) $args['forumId'];
        $pageSize = (int) ($args['pageSize'] ?? 10);
        $currentPage = (int) ($args['currentPage'] ?? 1);

        $sortOrder = $this->sortOrderBuilder
            ->setField('priority')
            ->setDirection('ASC')
            ->create();

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('status', TopicInterface::STATUS_ENABLED)
            ->addFilter('is_deleted', 0)
            ->setPageSize($pageSize)
            ->setCurrentPage($currentPage)
            ->addSortOrder($sortOrder)
            ->create();

        $result = $this->topicRepository->getListByForum($forumId, $searchCriteria);
        $items = [];

        foreach ($result->getItems() as $topic) {
            $items[] = $this->transformTopic($topic);
        }

        return [
            'items' => $items,
            'total_count' => $result->getTotalCount(),
            'page_info' => [
                'page_size' => $pageSize,
                'current_page' => $currentPage,
                'total_pages' => $result->getTotalCount() > 0
                    ? (int) ceil($result->getTotalCount() / $pageSize)
                    : 1,
            ],
        ];
    }

    private function transformTopic(TopicInterface $topic): array
    {
        return [
            'topic_id' => $topic->getId(),
            'forum_id' => $topic->getForumId(),
            'title' => $topic->getTitle(),
            'description' => $topic->getDescription(),
            'url_key' => $topic->getUrlKey(),
            'icon_id' => $topic->getIconId(),
            'status' => $topic->getStatus(),
            'is_sticky' => $topic->getIsSticky() ? 1 : 0,
            'total_views' => $topic->getTotalViews(),
            'total_posts' => $topic->getTotalPosts(),
            'last_post' => null,
            'first_post' => null,
            'created_at' => $topic->getCreatedAt(),
            'updated_at' => $topic->getUpdatedAt(),
        ];
    }
}
