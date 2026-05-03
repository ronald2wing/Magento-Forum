<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Customer;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class Subscriptions implements ActionInterface, HttpGetActionInterface
{
    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly PageFactory $resultPageFactory,
        private readonly CustomerSession $customerSession
    ) {}

    public function execute(): ResultInterface
    {
        if (!$this->customerSession->isLoggedIn()) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)
                ->setPath('customer/account/login');
        }
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('My Forum Subscriptions'));
        return $resultPage;
    }
}
