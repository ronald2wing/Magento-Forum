<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Ronald2Wing\Forum\Api\PostRepositoryInterface;

class ForumPost implements ResolverInterface
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository,
        private readonly PostTransformer $postTransformer
    ) {}

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        ?array $value = null,
        ?array $args = null
    ): array {
        $postId = (int) $args['postId'];
        $post = $this->postRepository->getById($postId);

        return $this->postTransformer->transform($post);
    }
}
