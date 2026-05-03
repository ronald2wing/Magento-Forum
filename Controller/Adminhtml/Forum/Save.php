<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Adminhtml\Forum;

use Ronald2Wing\Forum\Controller\Adminhtml\Index as AdminIndex;
use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Model\Service\UrlKeyGenerator;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Ronald2Wing\Forum\Model\ForumFactory;

class Save extends AdminIndex
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        private readonly PostDataProcessor $dataProcessor,
        private readonly UrlKeyGenerator $urlKeyGenerator,
        private readonly DateTime $date,
        private readonly FormKeyValidator $formKeyValidator,
        private readonly ForumFactory $forumFactory
    ) {
        parent::__construct($context, $coreRegistry, $resultPageFactory, $resultForwardFactory);
    }

    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Ronald2Wing_Forum:forum_save');
    }

    public function execute(): ResultInterface
    {
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $this->messageManager->addError(__('Invalid form key. Please refresh the page.'));
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }

        $data = $this->dataProcessor->filter((array) $data);

        $id = (int) $this->getRequest()->getParam('forum_id');
        $model = $this->forumFactory->create();

        if ($id) {
            $model->load($id);
            $data['updated_at'] = $this->date->gmtDate();
        } else {
            $data['created_at'] = $this->date->gmtDate();
        }

        $urlKeyValid = true;
        $errMessage = '';

        if (empty($data['url_key'])) {
            $data['url_key'] = $this->urlKeyGenerator->buildUniqueForumUrlKey((string) $data['title'], $id ?: null);
        } else {
            if (!$this->urlKeyGenerator->buildUniqueForumUrlKey((string) $data['url_key'], $id ?: null)) {
                $errMessage = __('Invalid url key for forum "%1", it already exists', $data['url_key']);
                $urlKeyValid = false;
            }
        }

        $model->setData($data);

        if (!$this->dataProcessor->validate($data) || !$urlKeyValid) {
            if ($errMessage) {
                $this->messageManager->addError($errMessage);
            }
            return $resultRedirect->setPath('*/*/edit', ['forum_id' => $model->getId(), '_current' => true]);
        }

        try {
            $model->save();
            $this->messageManager->addSuccess(__('You saved this forum.'));
            $this->_getSession()->setFormData(false);

            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['forum_id' => $model->getId(), '_current' => true]);
            }
            return $resultRedirect->setPath('*/*/');
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\RuntimeException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('Something went wrong while saving the forum.'));
        }

        $this->_getSession()->setFormData($data);
        return $resultRedirect->setPath('*/*/edit', ['forum_id' => $this->getRequest()->getParam('forum_id')]);
    }
}
