<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Block\Adminhtml\Topic\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;
use Ronald2Wing\Forum\Model\Source\Forum as ForumSource;

class Main extends Generic implements TabInterface
{
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        private readonly Store $systemStore,
        private readonly ForumSource $forumSource,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm(): Generic
    {
        $model = $this->_coreRegistry->registry('ronald2wing_forum_topic');

        $isElementDisabled = !$this->_isAllowedAction('Ronald2Wing_Forum::topic_save');

        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('topic_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Topic Information')]);

        if ($model->getId()) {
            $fieldset->addField('topic_id', 'hidden', ['name' => 'topic_id']);
        }

        $fieldset->addField('forum_id', 'select', [
            'name' => 'forum_id',
            'label' => __('Forum'),
            'title' => __('Forum'),
            'required' => true,
            'values' => $this->forumSource->toOptionArray(),
            'disabled' => $isElementDisabled,
        ]);

        $fieldset->addField('title', 'text', [
            'name' => 'title',
            'label' => __('Topic Title'),
            'title' => __('Topic Title'),
            'required' => true,
            'disabled' => $isElementDisabled,
        ]);

        $fieldset->addField('url_key', 'text', [
            'name' => 'url_key',
            'label' => __('URL Key'),
            'title' => __('URL Key'),
            'class' => 'validate-identifier',
            'disabled' => $isElementDisabled,
        ]);

        $fieldset->addField('status', 'select', [
            'label' => __('Status'),
            'title' => __('Topic Status'),
            'name' => 'status',
            'required' => true,
            'options' => ['1' => __('Enabled'), '0' => __('Disabled')],
            'disabled' => $isElementDisabled,
        ]);

        $fieldset->addField('description', 'textarea', [
            'label' => __('Description'),
            'title' => __('Topic Description'),
            'name' => 'description',
            'disabled' => $isElementDisabled,
        ]);

        if (!$model->getId()) {
            $model->setData('status', $isElementDisabled ? '0' : '1');
        }

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getTabLabel(): \Magento\Framework\Phrase
    {
        return __('Topic Information');
    }

    public function getTabTitle(): \Magento\Framework\Phrase
    {
        return __('Topic Information');
    }

    public function canShowTab(): bool
    {
        return true;
    }

    public function isHidden(): bool
    {
        return false;
    }

    protected function _isAllowedAction(string $resourceId): bool
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
