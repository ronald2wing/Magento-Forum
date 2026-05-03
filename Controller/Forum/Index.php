<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Forum;

use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Helper\Params;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Index implements ActionInterface, HttpGetActionInterface
{
    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly PageFactory $resultPageFactory,
        private readonly RequestInterface $request,
        private readonly Registry $registry,
        private readonly Params $params,
        private readonly CustomerSession $customerSession
    ) {}

    public function execute(): ResultInterface
    {
        $this->registry->register(
            Constant::PARAM_PAGE,
            $this->params->getPage(Constant::PAGER_FORUM)
        );
        $this->registry->register(
            Constant::PARAM_LIMIT,
            $this->params->getLimit(Constant::PAGER_FORUM, Constant::DEFAULT_PAGE_SIZE_FORUM)
        );
        $this->registry->register(
            Constant::PARAM_SORT,
            $this->params->getSort(Constant::PAGER_FORUM, Constant::SORT_CREATED_DESC)
        );
        $this->registry->register(Constant::REGISTRY_CUSTOMER_SESSION, $this->customerSession);

        $resultPage = $this->resultPageFactory->create();
        $breadcrumbs = $resultPage->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs) {
            $breadcrumbs->addCrumb('forum_home', [
                'label' => __('Forum'),
                'title' => __('Forum'),
            ]);
        }

        return $resultPage;
    }
}
