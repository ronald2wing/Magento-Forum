<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Customer;

use Ronald2Wing\Forum\Helper\Constant;
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
        private readonly CustomerSession $customerSession
    ) {}

    public function execute(): ResultInterface
    {
        if (!$this->customerSession->isLoggedIn()) {
            $this->customerSession->setAfterAuthUrl('/');
            $this->customerSession->authenticate();
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        $this->registry->register(Constant::REGISTRY_CUSTOMER_SESSION, $this->customerSession);

        return $this->resultPageFactory->create();
    }
}
