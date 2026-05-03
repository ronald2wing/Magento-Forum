<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Ronald2Wing\Forum\Api\ForumRepositoryInterface;
use Ronald2Wing\Forum\Api\Data\ForumInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;

class Forums implements ResolverInterface
{
    public function __construct(
        private readonly ForumRepositoryInterface $forumRepository,
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
        $pageSize = (int) ($args['pageSize'] ?? 10);
        $currentPage = (int) ($args['currentPage'] ?? 1);

        $sortOrder = $this->sortOrderBuilder
            ->setField('priority')
            ->setDirection('ASC')
            ->create();

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('status', ForumInterface::STATUS_ENABLED)
            ->addFilter('is_deleted', 0)
            ->setPageSize($pageSize)
            ->setCurrentPage($currentPage)
            ->addSortOrder($sortOrder)
            ->create();

        $result = $this->forumRepository->getList($searchCriteria);
        $items = [];

        foreach ($result->getItems() as $forum) {
            $items[] = $this->transformForum($forum);
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

    private function transformForum(ForumInterface $forum): array
    {
        return [
            'forum_id' => $forum->getId(),
            'parent_id' => $forum->getParentId(),
            'title' => $forum->getTitle(),
            'description' => $forum->getDescription(),
            'url_key' => $forum->getUrlKey(),
            'meta_description' => $forum->getMetaDescription(),
            'meta_keywords' => $forum->getMetaKeywords(),
            'icon_id' => $forum->getIconId(),
            'priority' => $forum->getPriority(),
            'status' => $forum->getStatus(),
            'store_id' => $forum->getStoreId(),
            'total_topics' => $forum->getTotalTopics(),
            'total_posts' => $forum->getTotalPosts(),
            'last_post' => null,
            'created_at' => $forum->getCreatedAt(),
            'updated_at' => $forum->getUpdatedAt(),
        ];
    }
}
