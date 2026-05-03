<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Api;

use Ronald2Wing\Forum\Api\Data\ForumInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface ForumRepositoryInterface
{
    public function save(ForumInterface $forum): ForumInterface;

    public function getById(int $forumId): ForumInterface;

    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    public function delete(ForumInterface $forum): bool;

    public function deleteById(int $forumId): bool;
}
