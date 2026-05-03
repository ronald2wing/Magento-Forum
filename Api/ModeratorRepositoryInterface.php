<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Api;

use Ronald2Wing\Forum\Api\Data\ModeratorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\NoSuchEntityException;

interface ModeratorRepositoryInterface
{
    public function save(ModeratorInterface $moderator): ModeratorInterface;

    public function getById(int $moderatorId): ModeratorInterface;

    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    public function delete(ModeratorInterface $moderator): bool;

    public function deleteById(int $moderatorId): bool;

    public function isModerator(int $userId): bool;

    public function addModerator(int $userId, ?int $websiteId): ModeratorInterface;

    public function removeModerator(int $userId): bool;
}
