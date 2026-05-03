<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model;

use Magento\Framework\Session\SessionManagerInterface;

class TopicView
{
    private const SESSION_KEY = 'forum_topic_views';

    public function __construct(
        private readonly SessionManagerInterface $forumSession,
        private readonly TopicRepository $topicRepository
    ) {}

    public function updateViews(int $topicId): void
    {
        $viewed = $this->forumSession->getData(self::SESSION_KEY, '');
        $viewedArray = $viewed ? unserialize($viewed, ['allowed_classes' => false]) : [];

        if (in_array($topicId, $viewedArray, true)) {
            return;
        }

        $viewedArray[] = $topicId;
        $this->forumSession->setData(self::SESSION_KEY, serialize($viewedArray));

        $topic = $this->topicRepository->getById($topicId);
        $topic->setTotalViews($topic->getTotalViews() + 1);
        $this->topicRepository->save($topic);
    }
}
