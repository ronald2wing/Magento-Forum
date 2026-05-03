<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Topic;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class NewAction implements ActionInterface, HttpGetActionInterface
{
    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly RequestInterface $request,
        private readonly CustomerSession $customerSession
    ) {}

    public function execute(): ResultInterface
    {
        if (!$this->customerSession->isLoggedIn()) {
            $this->customerSession->setAfterAuthUrl(
                $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->getUrl('*/*')
            );
            $this->customerSession->authenticate();
        }

        return $this->resultFactory->create(ResultFactory::TYPE_FORWARD)->forward('edit');
    }
}
