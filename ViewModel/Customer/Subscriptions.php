<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Customer;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Ronald2Wing\Forum\Model\ResourceModel\Notification\CollectionFactory;
use Ronald2Wing\Forum\Api\TopicRepositoryInterface;
use Ronald2Wing\Forum\Api\ForumRepositoryInterface;
use Ronald2Wing\Forum\Helper\Url as ForumUrlHelper;

class Subscriptions implements ArgumentInterface
{
    public function __construct(
        private readonly CollectionFactory $notificationCollectionFactory,
        private readonly TopicRepositoryInterface $topicRepository,
        private readonly ForumRepositoryInterface $forumRepository,
        private readonly ForumUrlHelper $forumUrlHelper,
        private readonly CustomerSession $customerSession
    ) {}

    public function getSubscriptions(): array
    {
        $userId = (int) $this->customerSession->getId();
        if (!$userId) {
            return [];
        }

        $collection = $this->notificationCollectionFactory->create();
        $collection->addFieldToFilter('user_id', $userId);

        $result = [];
        foreach ($collection as $notification) {
            try {
                $topic = $this->topicRepository->getById((int) $notification->getTopicId());
                $forum = $this->forumRepository->getById((int) $topic->getForumId());
                $result[] = [
                    'topic_id' => $topic->getId(),
                    'title' => $topic->getTitle(),
                    'url_key' => $topic->getUrlKey(),
                    'forum_title' => $forum->getTitle(),
                    'forum_url_key' => $forum->getUrlKey(),
                    'created_at' => $topic->getCreatedAt(),
                    'hash' => $notification->getUnsubscribeHash(),
                ];
            } catch (\Exception $e) {
                continue;
            }
        }
        return $result;
    }

    public function getUnsubscribeUrl(string $hash): string
    {
        return $this->forumUrlHelper->getUnsubscribeUrl($hash);
    }

    public function getTopicUrl(string $forumUrlKey, string $topicUrlKey): string
    {
        return $this->forumUrlHelper->getTopicUrl($forumUrlKey, $topicUrlKey);
    }
}
