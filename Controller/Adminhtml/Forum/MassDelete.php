<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Adminhtml\Forum;

use Ronald2Wing\Forum\Controller\Adminhtml\Index as AdminIndex;
use Magento\Framework\Controller\ResultInterface;
use Ronald2Wing\Forum\Model\ForumFactory;
use Ronald2Wing\Forum\Model\Service\CounterUpdater;

class MassDelete extends AdminIndex
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        private readonly CounterUpdater $counterUpdater,
        private readonly ForumFactory $forumFactory
    ) {
        parent::__construct($context, $coreRegistry, $resultPageFactory, $resultForwardFactory);
    }

    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Ronald2Wing_Forum:forum_delete');
    }

    public function execute(): ResultInterface
    {
        $ids = $this->getRequest()->getParam('selected');
        if (!is_array($ids)) {
            $ids = $this->getRequest()->getParam('excluded');
            if (!is_array($ids)) {
                $this->messageManager->addError(__('Please select item(s).'));
                return $this->resultRedirectFactory->create()->setPath('*/*/index');
            }
        }

        $count = 0;
        foreach ($ids as $id) {
            try {
                $model = $this->forumFactory->create();
                $model->load((int) $id);
                $model->setIsDeleted(true);
                $model->save();
                $this->counterUpdater->updateForumCounts($model->getId());
                $count++;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $count));

        return $this->resultRedirectFactory->create()->setPath('*/*/index');
    }
}
