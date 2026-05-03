<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Adminhtml\Forum;

use Ronald2Wing\Forum\Controller\Adminhtml\Index as AdminIndex;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;

class Edit extends AdminIndex
{
    public function execute(): ResultInterface
    {
        $id = (int) $this->getRequest()->getParam('forum_id');
        $model = $this->_objectManager->create(\Ronald2Wing\Forum\Model\Forum::class);

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This forum no longer exists.'));
                return $this->resultRedirectFactory->create()->setPath('*/*/');
            }
        }

        $this->coreRegistry->register('ronald2wing_forum_forum', $model);

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $title = $model->getId()
            ? __('Edit Forum: %1', $model->getTitle())
            : __('New Forum');
        $resultPage->getConfig()->getTitle()->prepend($title);
        $resultPage->addBreadcrumb(__('Forum'), __('Forum'));
        $resultPage->addBreadcrumb($title, $title);

        return $resultPage;
    }
}
