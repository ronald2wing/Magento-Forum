<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Search;

use Ronald2Wing\Forum\Helper\Constant;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Reset implements ActionInterface, HttpGetActionInterface
{
    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly SessionManagerInterface $forumSession
    ) {}

    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $this->forumSession->setData(Constant::REGISTRY_SEARCH_QUERY, null);
        $resultRedirect->setUrl('/');
        return $resultRedirect;
    }
}
