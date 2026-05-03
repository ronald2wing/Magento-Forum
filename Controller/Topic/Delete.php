<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Topic;

use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Helper\Data as ForumData;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Ronald2Wing\Forum\Model\PostFactory;
use Ronald2Wing\Forum\Model\Service\AuthorisationService;
use Ronald2Wing\Forum\Model\Service\CounterUpdater;
use Ronald2Wing\Forum\Model\TopicFactory;

class Delete implements ActionInterface, HttpGetActionInterface
{
    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly RequestInterface $request,
        private readonly CustomerSession $customerSession,
        private readonly TopicFactory $topicFactory,
        private readonly PostFactory $postFactory,
        private readonly ForumData $forumData,
        private readonly AuthorisationService $authService,
        private readonly CounterUpdater $counterUpdater,
        private readonly ManagerInterface $messageManager
    ) {}

    public function execute(): ResultInterface
    {
        if (!$this->customerSession->isLoggedIn()) {
            $this->customerSession->setAfterAuthUrl('/');
            $this->customerSession->authenticate();
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        $topicId = (int) $this->request->getParam(Constant::PARAM_TOPIC_ID);
        $topic = $this->topicFactory->create();
        $topic->load($topicId);

        if (!$topic->getStatus() || $topic->getIsDeleted() || !$this->authService->canModify($topic)) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        $totalPostsDeleted = $this->deletePosts($topic);
        $topic->setIsDeleted(true);
        $topic->save();

        $this->counterUpdater->updateTopicCounts($topic->getId());
        $this->counterUpdater->updateForumCounts($topic->getForumId());

        $this->messageManager->addSuccess(__('You successfully deleted topic'));

        if ($totalPostsDeleted) {
            $this->messageManager->addSuccess(__('Total %1 post(s) deleted', $totalPostsDeleted));
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
    }

    private function deletePosts(\Ronald2Wing\Forum\Model\Topic $topic): int
    {
        $collection = $this->postFactory->create()->getCollection();
        $collection->getSelect()->where('topic_id=? AND is_deleted=0', (int) $topic->getId());
        $count = 0;
        foreach ($collection as $itemDel) {
            $model = $this->postFactory->create();
            $model->load((int) $itemDel->getId());
            $model->setIsDeleted(true);
            $model->save();
            $count++;
        }
        return $count;
    }
}
