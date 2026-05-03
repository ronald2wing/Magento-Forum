<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Adminhtml\Topic;

use Ronald2Wing\Forum\Controller\Adminhtml\Index as AdminIndex;
use Magento\Framework\Controller\ResultInterface;
use Ronald2Wing\Forum\Model\TopicFactory;

class Delete extends AdminIndex
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        private readonly TopicFactory $topicFactory
    ) {
        parent::__construct($context, $coreRegistry, $resultPageFactory, $resultForwardFactory);
    }

    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Ronald2Wing_Forum:topic_delete');
    }

    public function execute(): ResultInterface
    {
        $id = (int) $this->getRequest()->getParam('topic_id');
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                $model = $this->topicFactory->create();
                $model->load($id);
                $model->setIsDeleted(true);
                $model->save();
                $this->messageManager->addSuccess(__('You deleted the topic.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['topic_id' => $id]);
            }
        }

        $this->messageManager->addError(__('We can\'t find a topic to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
