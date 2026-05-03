<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

abstract class Index extends Action
{
    public function __construct(
        Context $context,
        protected readonly Registry $coreRegistry,
        protected readonly PageFactory $resultPageFactory,
        protected readonly ForwardFactory $resultForwardFactory
    ) {
        parent::__construct($context);
    }

    protected function initPageForum(Page $resultPage): Page
    {
        $resultPage->setActiveMenu('Ronald2Wing_Forum::forum_manager')
            ->addBreadcrumb(__('Forum'), __('Forum'));
        return $resultPage;
    }
}
