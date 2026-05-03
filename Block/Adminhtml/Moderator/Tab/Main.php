<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Block\Adminhtml\Moderator\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;

class Main extends Generic implements TabInterface
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
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('moderator_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Add Moderator')]);

        $fieldset->addField('user_id', 'text', [
            'name' => 'user_id',
            'label' => __('User ID'),
            'title' => __('User ID'),
            'required' => true,
            'class' => 'validate-digits',
        ]);

        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getTabLabel(): \Magento\Framework\Phrase
    {
        return __('Add Moderator');
    }

    public function getTabTitle(): \Magento\Framework\Phrase
    {
        return __('Add Moderator');
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
