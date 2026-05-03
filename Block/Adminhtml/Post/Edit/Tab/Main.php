<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Block\Adminhtml\Post\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Ronald2Wing\Forum\Model\Source\Forum as ForumSource;

class Main extends Generic implements TabInterface
{
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        private readonly ForumSource $forumSource,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm(): Generic
    {
        $model = $this->_coreRegistry->registry('ronald2wing_forum_post');

        $isElementDisabled = !$this->_isAllowedAction('Ronald2Wing_Forum::post_save');

        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('post_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Post Information')]);

        if ($model->getId()) {
            $fieldset->addField('post_id', 'hidden', ['name' => 'post_id']);
        }

        $fieldset->addField('forum_id', 'select', [
            'name' => 'forum_id',
            'label' => __('Forum'),
            'title' => __('Forum'),
            'required' => true,
            'values' => $this->forumSource->toOptionArray(),
            'disabled' => $isElementDisabled,
        ]);

        $fieldset->addField('topic_id', 'text', [
            'name' => 'topic_id',
            'label' => __('Topic ID'),
            'title' => __('Topic ID'),
            'disabled' => $isElementDisabled,
        ]);

        $fieldset->addField('content', 'editor', [
            'name' => 'content',
            'label' => __('Content'),
            'title' => __('Content'),
            'required' => true,
            'config' => $this->_wysiwygConfig->getConfig(),
            'disabled' => $isElementDisabled,
        ]);

        $fieldset->addField('status', 'select', [
            'label' => __('Status'),
            'title' => __('Post Status'),
            'name' => 'status',
            'required' => true,
            'options' => ['1' => __('Enabled'), '0' => __('Disabled')],
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
        return __('Post Information');
    }

    public function getTabTitle(): \Magento\Framework\Phrase
    {
        return __('Post Information');
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
