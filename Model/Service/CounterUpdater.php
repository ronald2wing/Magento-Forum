<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\Service;

use Ronald2Wing\Forum\Api\Data\PostInterface;
use Ronald2Wing\Forum\Api\Data\TopicInterface;
use Ronald2Wing\Forum\Api\ForumRepositoryInterface;
use Ronald2Wing\Forum\Api\TopicRepositoryInterface;
use Ronald2Wing\Forum\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Topic\CollectionFactory as TopicCollectionFactory;

class CounterUpdater
{
    public function __construct(
        private readonly ForumRepositoryInterface $forumRepository,
        private readonly TopicRepositoryInterface $topicRepository,
        private readonly PostCollectionFactory $postCollectionFactory,
        private readonly TopicCollectionFactory $topicCollectionFactory
    ) {}

    public function updateTopicCounts(int $topicId): void
    {
        $collection = $this->postCollectionFactory->create();
        $collection->addFieldToFilter('topic_id', $topicId);
        $collection->addFieldToFilter('is_deleted', 0);
        $collection->addFieldToFilter('status', PostInterface::STATUS_ENABLED);
        $collection->setOrder('post_id', 'DESC');
        $collection->setPageSize(1);

        $totalPosts = (int) $collection->getSize();
        $lastPost = $collection->getFirstItem();
        $lastPostId = $lastPost->getId();

        $topic = $this->topicRepository->getById($topicId);
        $topic->setTotalPosts($totalPosts);
        $topic->setLastPostId($lastPostId ? (int) $lastPostId : null);
        $this->topicRepository->save($topic);

        if ($forumId = $topic->getForumId()) {
            $this->updateForumCounts($forumId);
        }
    }

    public function updateForumCounts(int $forumId): void
    {
        $collection = $this->postCollectionFactory->create();
        $collection->addFieldToFilter('forum_id', $forumId);
        $collection->addFieldToFilter('is_deleted', 0);
        $collection->addFieldToFilter('status', PostInterface::STATUS_ENABLED);
        $collection->setOrder('post_id', 'DESC');
        $collection->setPageSize(1);

        $totalPosts = (int) $collection->getSize();
        $lastPost = $collection->getFirstItem();
        $lastPostId = $lastPost->getId();

        $topicCollection = $this->topicCollectionFactory->create();
        $topicCollection->addFieldToFilter('forum_id', $forumId);
        $topicCollection->addFieldToFilter('is_deleted', 0);
        $topicCollection->addFieldToFilter('status', TopicInterface::STATUS_ENABLED);
        $totalTopics = (int) $topicCollection->getSize();

        $forum = $this->forumRepository->getById($forumId);
        $forum->setTotalPosts($totalPosts);
        $forum->setTotalTopics($totalTopics);
        $forum->setLastPostId($lastPostId ? (int) $lastPostId : null);
        $this->forumRepository->save($forum);
    }
}
