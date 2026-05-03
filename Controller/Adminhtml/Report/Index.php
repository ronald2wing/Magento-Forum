<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Adminhtml\Report;

use Ronald2Wing\Forum\Controller\Adminhtml\Index as AdminIndex;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;

class Index extends AdminIndex
{
    public function execute(): ResultInterface
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ronald2Wing_Forum::report_manage');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Reports'));
        $resultPage->addBreadcrumb(__('Forum'), __('Forum'));
        $resultPage->addBreadcrumb(__('Report Manager'), __('Report Manager'));

        return $resultPage;
    }
}
