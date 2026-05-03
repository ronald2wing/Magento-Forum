<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Post;

use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Helper\Url as ForumUrl;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Ronald2Wing\Forum\Model\PostFactory;
use Ronald2Wing\Forum\Model\TopicFactory;
use Ronald2Wing\Forum\Model\ForumFactory;

class View implements ActionInterface, HttpGetActionInterface
{
    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly RequestInterface $request,
        private readonly PostFactory $postFactory,
        private readonly TopicFactory $topicFactory,
        private readonly ForumFactory $forumFactory,
        private readonly ForumUrl $url
    ) {}

    public function execute(): ResultInterface
    {
        $postId = (int) $this->request->getParam(Constant::PARAM_POST_ID);
        if (!$postId) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        $postModel = $this->postFactory->create();
        $postModel->load($postId);

        if (!$postModel->getId() || $postModel->getIsDeleted()) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        $topicModel = $this->topicFactory->create();
        $topicModel->load((int) $postModel->getTopicId());

        if (!$topicModel->getId() || $topicModel->getIsDeleted()) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        $forumModel = $this->forumFactory->create();
        $forumModel->load((int) $topicModel->getForumId());

        if (!$forumModel->getId() || $forumModel->getIsDeleted()) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)
            ->setUrl($this->url->getTopicUrl(
                (string) $forumModel->getUrlKey(),
                (string) $topicModel->getUrlKey()
            ) . '#post-' . $postId);
    }
}
