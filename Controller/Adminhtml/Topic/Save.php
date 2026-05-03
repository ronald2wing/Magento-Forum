<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Adminhtml\Topic;

use Ronald2Wing\Forum\Controller\Adminhtml\Index as AdminIndex;
use Ronald2Wing\Forum\Model\Service\UrlKeyGenerator;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Ronald2Wing\Forum\Model\TopicFactory;

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
        private readonly TopicFactory $topicFactory
    ) {
        parent::__construct($context, $coreRegistry, $resultPageFactory, $resultForwardFactory);
    }

    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Ronald2Wing_Forum:topic_save');
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

        $id = (int) $this->getRequest()->getParam('topic_id');
        $model = $this->topicFactory->create();

        if ($id) {
            $model->load($id);
            $data['updated_at'] = $this->date->gmtDate();
        } else {
            $data['created_at'] = $this->date->gmtDate();
        }

        if (empty($data['url_key'])) {
            $forumId = (int) ($data['forum_id'] ?? 0);
            $data['url_key'] = $this->urlKeyGenerator->buildUniqueTopicUrlKey(
                (string) $data['title'],
                $forumId,
                $id ?: null
            );
        }

        $model->setData($data);

        if (!$this->dataProcessor->validate($data)) {
            return $resultRedirect->setPath('*/*/edit', ['topic_id' => $model->getId(), '_current' => true]);
        }

        try {
            $model->save();
            $this->messageManager->addSuccess(__('You saved this topic.'));
            $this->_getSession()->setFormData(false);

            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['topic_id' => $model->getId(), '_current' => true]);
            }
            return $resultRedirect->setPath('*/*/');
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\RuntimeException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('Something went wrong while saving the topic.'));
        }

        $this->_getSession()->setFormData($data);
        return $resultRedirect->setPath('*/*/edit', ['topic_id' => $this->getRequest()->getParam('topic_id')]);
    }
}
