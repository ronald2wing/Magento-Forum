<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Api;

use Ronald2Wing\Forum\Api\Data\PostInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\NoSuchEntityException;

interface PostRepositoryInterface
{
    public function save(PostInterface $post): PostInterface;

    public function getById(int $postId): PostInterface;

    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    public function delete(PostInterface $post): bool;

    public function deleteById(int $postId): bool;

    public function getListByTopic(int $topicId, SearchCriteriaInterface $searchCriteria): SearchResultsInterface;
}
