<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Adminhtml\Forum;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class NewAction extends Action
{
    public function execute(): ResultInterface
    {
        return $this->resultFactory->create(ResultFactory::TYPE_FORWARD)->forward('edit');
    }
}
