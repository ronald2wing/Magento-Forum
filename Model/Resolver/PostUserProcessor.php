<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\Resolver;

use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Ronald2Wing\Forum\Model\ResourceModel\UserSettings\CollectionFactory as UserSettingsCollectionFactory;

class PostUserProcessor
{
    public function __construct(
        private readonly UserSettingsCollectionFactory $usersettingsCollectionFactory
    ) {}

    public function getUser(array $postData): array
    {
        $userId = $postData['user_id'] ?? null;
        if ($userId === null) {
            return [];
        }

        $usersettingsCollection = $this->usersettingsCollectionFactory->create();
        $usersettingsCollection->addFieldToFilter('user_id', $userId);
        $usersettings = $usersettingsCollection->getFirstItem();

        return [
            'user_id' => $userId,
            'nickname' => $usersettings->getNickname(),
            'signature' => $usersettings->getSignature(),
            'avatar_url' => $usersettings->getAvatar(),
        ];
    }
}
