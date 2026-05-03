<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Adminhtml\Adminsettings;

use Ronald2Wing\Forum\Controller\Adminhtml\Index as AdminIndex;
use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Model\UserSettingsFactory;
use Ronald2Wing\Forum\Model\ResourceModel\UserSettings as UserSettingsResource;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;

class Save extends AdminIndex
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        private readonly FormKeyValidator $formKeyValidator,
        private readonly UserSettingsFactory $userSettingsFactory,
        private readonly UserSettingsResource $userSettingsResource
    ) {
        parent::__construct($context, $coreRegistry, $resultPageFactory, $resultForwardFactory);
    }

    public function execute(): ResultInterface
    {
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $this->messageManager->addError(__('Invalid form key. Please refresh the page.'));
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        $nickname = strip_tags((string) $this->getRequest()->getParam('nickname', ''));
        $signature = strip_tags((string) $this->getRequest()->getParam('signature', ''));

        $settings = $this->userSettingsFactory->create();
        $this->userSettingsResource->load($settings, Constant::ADMIN_USER_ID, 'user_id');
        if (!$settings->getId()) {
            $settings->setUserId(Constant::ADMIN_USER_ID);
        }
        $settings->setNickname($nickname);
        $settings->setSignature($signature);
        $this->userSettingsResource->save($settings);
        $this->messageManager->addSuccessMessage(__('Settings saved.'));

        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
