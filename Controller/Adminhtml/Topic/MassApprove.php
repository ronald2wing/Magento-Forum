<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Adminhtml\Topic;

use Ronald2Wing\Forum\Controller\Adminhtml\Index as AdminIndex;
use Magento\Framework\Controller\ResultInterface;
use Ronald2Wing\Forum\Model\TopicFactory;
use Ronald2Wing\Forum\Model\ForumFactory;
use Ronald2Wing\Forum\Model\Service\NotificationService;
use Ronald2Wing\Forum\Helper\Url as ForumUrl;
use Ronald2Wing\Forum\Helper\Data as ForumData;
use Ronald2Wing\Forum\Api\Data\TopicInterface;

class MassApprove extends AdminIndex
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        private readonly TopicFactory $topicFactory,
        private readonly NotificationService $notificationService,
        private readonly ForumFactory $forumFactory,
        private readonly ForumUrl $helperUrl,
        private readonly ForumData $forumData
    ) {
        parent::__construct($context, $coreRegistry, $resultPageFactory, $resultForwardFactory);
    }

    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Ronald2Wing_Forum:topic_save');
    }

    public function execute(): ResultInterface
    {
        $ids = $this->getRequest()->getParam('selected');
        if (!is_array($ids)) {
            $this->messageManager->addError(__('Please select item(s).'));
            return $this->resultRedirectFactory->create()->setPath('*/*/index');
        }

        $count = 0;
        foreach ($ids as $id) {
            try {
                $model = $this->topicFactory->create();
                $model->load((int) $id);
                $model->setStatus(TopicInterface::STATUS_ENABLED);
                $model->save();
                $count++;

                if ($this->forumData->isNotifyCustomerEnabled()) {
                    $forum = $this->forumFactory->create();
                    $forum->load((int) $model->getForumId());
                    $postPreview = mb_substr((string) $model->getDescription(), 0, 200);
                    $this->notificationService->sendNotification(
                        (int) $model->getId(),
                        (string) $model->getTitle(),
                        $this->helperUrl->getTopicUrl(
                            (string) $forum->getUrlKey(),
                            (string) $model->getUrlKey()
                        ),
                        $postPreview
                    );
                }
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been approved.', $count));

        return $this->resultRedirectFactory->create()->setPath('*/*/index');
    }
}
