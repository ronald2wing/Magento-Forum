<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Adminhtml\Moderator;

use Ronald2Wing\Forum\Controller\Adminhtml\Index as AdminIndex;
use Magento\Framework\Controller\ResultInterface;
use Ronald2Wing\Forum\Api\ModeratorRepositoryInterface;

class Add extends AdminIndex
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        private readonly ModeratorRepositoryInterface $moderatorRepository
    ) {
        parent::__construct($context, $coreRegistry, $resultPageFactory, $resultForwardFactory);
    }

    public function execute(): ResultInterface
    {
        $userId = (int) $this->getRequest()->getParam('user_id');
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($userId) {
            try {
                $this->moderatorRepository->addModerator($userId, null);
                $this->messageManager->addSuccess(__('Moderator added successfully.'));
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}
