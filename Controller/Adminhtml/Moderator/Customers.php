<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Adminhtml\Moderator;

use Ronald2Wing\Forum\Controller\Adminhtml\Index as AdminIndex;
use Magento\Framework\Controller\ResultInterface;

class Customers extends AdminIndex
{
    public function execute(): ResultInterface
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Select Customer'));
        return $resultPage;
    }
}
