<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Adminhtml\Moderator;

use Ronald2Wing\Forum\Controller\Adminhtml\Index as AdminIndex;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;

class Index extends AdminIndex
{
    public function execute(): ResultInterface
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ronald2Wing_Forum::moderator');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Moderators'));
        $resultPage->addBreadcrumb(__('Forum'), __('Forum'));
        $resultPage->addBreadcrumb(__('Moderator Manager'), __('Moderator Manager'));

        return $resultPage;
    }
}
