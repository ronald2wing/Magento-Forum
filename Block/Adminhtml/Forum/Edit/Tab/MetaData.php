<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Block\Adminhtml\Forum\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;

class MetaData extends Generic implements TabInterface
{
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm(): Generic
    {
        $model = $this->_coreRegistry->registry('ronald2wing_forum_forum');
        $form = $this->_formFactory->create();

        $fieldset = $form->addFieldset('meta_fieldset', ['legend' => __('Meta Data')]);

        $fieldset->addField('meta_description', 'textarea', [
            'name' => 'meta_description',
            'label' => __('Meta Description'),
            'title' => __('Meta Description'),
        ]);

        $fieldset->addField('meta_keywords', 'textarea', [
            'name' => 'meta_keywords',
            'label' => __('Meta Keywords'),
            'title' => __('Meta Keywords'),
        ]);

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getTabLabel(): \Magento\Framework\Phrase
    {
        return __('Meta Data');
    }

    public function getTabTitle(): \Magento\Framework\Phrase
    {
        return __('Meta Data');
    }

    public function canShowTab(): bool
    {
        return true;
    }

    public function isHidden(): bool
    {
        return false;
    }
}
