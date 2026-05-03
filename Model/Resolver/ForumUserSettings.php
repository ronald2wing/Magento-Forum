<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Ronald2Wing\Forum\Api\ModeratorRepositoryInterface;
use Ronald2Wing\Forum\Model\ResourceModel\UserSettings\CollectionFactory as UserSettingsCollectionFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Topic\CollectionFactory as TopicCollectionFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;

class ForumUserSettings implements ResolverInterface
{
    public function __construct(
        private readonly ModeratorRepositoryInterface $moderatorRepository,
        private readonly UserSettingsCollectionFactory $usersettingsCollectionFactory,
        private readonly TopicCollectionFactory $topicCollectionFactory,
        private readonly PostCollectionFactory $postCollectionFactory
    ) {}

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        ?array $value = null,
        ?array $args = null
    ): array {
        $userId = (int) $args['userId'];

        $usersettingsCollection = $this->usersettingsCollectionFactory->create();
        $usersettingsCollection->addFieldToFilter('user_id', $userId);
        $usersettings = $usersettingsCollection->getFirstItem();

        $topicCollection = $this->topicCollectionFactory->create();
        $topicCollection->addFieldToFilter('user_id', $userId);
        $topicCollection->addFieldToFilter('is_deleted', 0);
        $totalTopics = $topicCollection->getSize();

        $postCollection = $this->postCollectionFactory->create();
        $postCollection->addFieldToFilter('user_id', $userId);
        $postCollection->addFieldToFilter('is_deleted', 0);
        $totalPosts = $postCollection->getSize();

        $isModerator = $this->moderatorRepository->isModerator($userId);
        $role = $isModerator ? 'Moderator' : 'User';

        return [
            'user_id' => $userId,
            'nickname' => $usersettings->getNickname(),
            'signature' => $usersettings->getSignature(),
            'avatar_url' => $usersettings->getAvatar(),
            'total_topics' => $totalTopics,
            'total_posts' => $totalPosts,
            'role' => $role,
        ];
    }
}
