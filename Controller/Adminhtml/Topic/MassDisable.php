<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Adminhtml\Topic;

use Ronald2Wing\Forum\Controller\Adminhtml\Index as AdminIndex;
use Magento\Framework\Controller\ResultInterface;
use Ronald2Wing\Forum\Model\TopicFactory;
use Ronald2Wing\Forum\Api\Data\TopicInterface;

class MassDisable extends AdminIndex
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
                $model->setStatus(TopicInterface::STATUS_DISABLED);
                $model->save();
                $count++;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been disabled.', $count));

        return $this->resultRedirectFactory->create()->setPath('*/*/index');
    }
}
