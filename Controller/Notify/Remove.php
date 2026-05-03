<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Notify;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Registry;
use Ronald2Wing\Forum\Model\Service\NotificationService;

class Remove implements ActionInterface, HttpGetActionInterface
{
    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly RequestInterface $request,
        private readonly NotificationService $notificationService,
        private readonly Registry $coreRegistry,
        private readonly ManagerInterface $messageManager
    ) {}

    public function execute(): ResultInterface
    {
        $hash = (string) $this->request->getParam('hash');
        $removed = $this->notificationService->unsubscribeByHash($hash);

        $this->coreRegistry->register('forum_notify_remove_success', $removed);

        if ($removed) {
            $this->messageManager->addSuccess(__('You have been unsubscribed from this topic.'));
        } else {
            $this->messageManager->addError(__('Invalid or expired unsubscribe link.'));
        }

        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
