<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model;

use Ronald2Wing\Forum\Api\Data\ModeratorInterface;
use Ronald2Wing\Forum\Api\ModeratorRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class ModeratorRepository extends AbstractRepository implements ModeratorRepositoryInterface
{
    public function __construct(
        private readonly ResourceModel\Moderator $resource,
        private readonly ModeratorFactory $moderatorFactory,
        private readonly ResourceModel\Moderator\CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory
    ) {
        parent::__construct($searchResultsFactory);
    }

    public function save(ModeratorInterface $moderator): ModeratorInterface
    {
        try {
            $this->resource->save($moderator);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not save moderator: %1', $e->getMessage()));
        }
        return $moderator;
    }

    public function getById(int $moderatorId): ModeratorInterface
    {
        $moderator = $this->moderatorFactory->create();
        $this->resource->load($moderator, $moderatorId);
        if (!$moderator->getId()) {
            throw new NoSuchEntityException(__('Moderator with id "%1" does not exist.', $moderatorId));
        }
        return $moderator;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        return $this->doGetList($searchCriteria, $collection);
    }

    public function delete(ModeratorInterface $moderator): bool
    {
        try {
            $this->resource->delete($moderator);
            return true;
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete moderator: %1', $e->getMessage()));
        }
    }

    public function deleteById(int $moderatorId): bool
    {
        return $this->delete($this->getById($moderatorId));
    }

    public function isModerator(int $userId): bool
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('user_id', $userId);

        return $collection->getSize() > 0;
    }

    public function addModerator(int $userId, ?int $websiteId): ModeratorInterface
    {
        $moderator = $this->moderatorFactory->create();
        $moderator->setUserId($userId);
        $moderator->setWebsiteId($websiteId);

        try {
            $this->resource->save($moderator);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not add moderator: %1', $e->getMessage()));
        }

        return $moderator;
    }

    public function removeModerator(int $userId): bool
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('user_id', $userId);
        $moderator = $collection->getFirstItem();

        if (!$moderator->getId()) {
            throw new NoSuchEntityException(__('Moderator with user id "%1" does not exist.', $userId));
        }

        return $this->delete($moderator);
    }
}
