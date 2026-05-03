<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Adminhtml\Post;

use Ronald2Wing\Forum\Controller\Adminhtml\Index as AdminIndex;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;

class Index extends AdminIndex
{
    public function execute(): ResultInterface
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ronald2Wing_Forum::post');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Posts'));
        $resultPage->addBreadcrumb(__('Forum'), __('Forum'));
        $resultPage->addBreadcrumb(__('Post Manager'), __('Post Manager'));

        return $resultPage;
    }
}
