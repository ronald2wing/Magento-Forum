<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Api;

use Ronald2Wing\Forum\Api\Data\TopicInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\NoSuchEntityException;

interface TopicRepositoryInterface
{
    public function save(TopicInterface $topic): TopicInterface;

    public function getById(int $topicId): TopicInterface;

    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    public function delete(TopicInterface $topic): bool;

    public function deleteById(int $topicId): bool;

    public function getByUrlKey(int $forumId, string $urlKey): TopicInterface;

    public function getListByForum(int $forumId, SearchCriteriaInterface $searchCriteria): SearchResultsInterface;
}
