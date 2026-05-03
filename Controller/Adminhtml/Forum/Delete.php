<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Adminhtml\Forum;

use Ronald2Wing\Forum\Controller\Adminhtml\Index as AdminIndex;
use Magento\Framework\Controller\ResultInterface;
use Ronald2Wing\Forum\Model\ForumFactory;

class Delete extends AdminIndex
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
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
        $id = (int) $this->getRequest()->getParam('forum_id');
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                $model = $this->forumFactory->create();
                $model->load($id);
                $model->setIsDeleted(true);
                $model->save();
                $this->messageManager->addSuccess(__('You deleted the forum.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['forum_id' => $id]);
            }
        }

        $this->messageManager->addError(__('We can\'t find a forum to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
