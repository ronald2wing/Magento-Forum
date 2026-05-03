<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Block\Adminhtml\Adminsettings\Edit\Tab;

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
        $form->setHtmlIdPrefix('adminsettings_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Admin Settings')]);

        $fieldset->addField('admin_user_id', 'text', [
            'name' => 'admin_user_id',
            'label' => __('Admin User ID'),
            'title' => __('Admin User ID'),
            'class' => 'validate-digits',
            'note' => __('The admin user ID used for forum admin posts (default: 0)'),
        ]);

        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getTabLabel(): \Magento\Framework\Phrase
    {
        return __('General');
    }

    public function getTabTitle(): \Magento\Framework\Phrase
    {
        return __('General');
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
