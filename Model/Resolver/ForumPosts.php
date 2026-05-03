<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Ronald2Wing\Forum\Api\PostRepositoryInterface;
use Ronald2Wing\Forum\Api\Data\PostInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;

class ForumPosts implements ResolverInterface
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly SortOrderBuilder $sortOrderBuilder,
        private readonly PostTransformer $postTransformer
    ) {}

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        ?array $value = null,
        ?array $args = null
    ): array {
        $topicId = (int) $args['topicId'];
        $pageSize = (int) ($args['pageSize'] ?? 10);
        $currentPage = (int) ($args['currentPage'] ?? 1);

        $sortOrder = $this->sortOrderBuilder
            ->setField('created_at')
            ->setDirection('ASC')
            ->create();

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('status', PostInterface::STATUS_ENABLED)
            ->addFilter('is_deleted', 0)
            ->setPageSize($pageSize)
            ->setCurrentPage($currentPage)
            ->addSortOrder($sortOrder)
            ->create();

        $result = $this->postRepository->getListByTopic($topicId, $searchCriteria);
        $items = [];

        foreach ($result->getItems() as $post) {
            $items[] = $this->postTransformer->transform($post);
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
}
