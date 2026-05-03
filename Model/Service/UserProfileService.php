<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\Service;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Ronald2Wing\Forum\Model\UserSettingsFactory;
use Ronald2Wing\Forum\Model\ResourceModel\UserSettings as UserSettingsResource;
use Ronald2Wing\Forum\Model\ResourceModel\Topic\CollectionFactory as TopicCollectionFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use Ronald2Wing\Forum\Model\ModeratorFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Moderator as ModeratorResource;
use Ronald2Wing\Forum\Model\UserSettings;

class UserProfileService
{
    private array $usersLoaded = [];

    public function __construct(
        private readonly UserSettingsFactory $usersettingsFactory,
        private readonly UserSettingsResource $usersettingsResource,
        private readonly TopicCollectionFactory $topicCollectionFactory,
        private readonly PostCollectionFactory $postCollectionFactory,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly ModeratorFactory $moderatorFactory,
        private readonly ModeratorResource $moderatorResource,
        private readonly StoreManagerInterface $storeManager
    ) {}

    public function getUserSettings(int $userId): UserSettings
    {
        if (!isset($this->usersLoaded[$userId])) {
            $settings = $this->usersettingsFactory->create();
            $settings->setUserId($userId);
            $this->usersettingsResource->load($settings, $userId, 'user_id');
            $this->usersLoaded[$userId] = $settings;
        }

        return $this->usersLoaded[$userId];
    }

    public function getTotalTopics(int $userId): int
    {
        $collection = $this->topicCollectionFactory->create();
        $collection->addFieldToFilter('user_id', $userId);
        $collection->addFieldToFilter('is_deleted', 0);

        return (int) $collection->getSize();
    }

    public function getTotalPosts(int $userId): int
    {
        $collection = $this->postCollectionFactory->create();
        $collection->addFieldToFilter('user_id', $userId);
        $collection->addFieldToFilter('is_deleted', 0);

        return (int) $collection->getSize();
    }

    public function getRole(int $userId): string
    {
        $moderator = $this->moderatorFactory->create();
        $this->moderatorResource->load($moderator, $userId, 'user_id');

        if ($moderator->getId()) {
            return 'Moderator';
        }

        return 'User';
    }

    public function getCustomerName(int $customerId): string
    {
        try {
            $customer = $this->customerRepository->getById($customerId);

            return $customer->getFirstname() . ' ' . $customer->getLastname();
        } catch (\Exception $e) {
            return '';
        }
    }

    public function getAvatarUrl(UserSettings $settings): string
    {
        $avatar = $settings->getAvatar();

        if (!$avatar) {
            return '';
        }

        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
            . 'ronald2wingforum/avatar/' . $avatar;
    }
}
