<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Ronald2Wing\Forum\Model\Service\CounterUpdater;

class PostSaveAfter implements ObserverInterface
{
    public function __construct(
        private readonly CounterUpdater $counterUpdater
    ) {}

    public function execute(Observer $observer): void
    {
        $post = $observer->getData('post');
        if ($post && $post->getId()) {
            $topicId = $post->getTopicId();
            if ($topicId) {
                $this->counterUpdater->updateTopicCounts((int) $topicId);
            }

            $forumId = $post->getForumId();
            if ($forumId) {
                $this->counterUpdater->updateForumCounts((int) $forumId);
            }
        }
    }
}
