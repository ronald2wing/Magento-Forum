<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Block\Adminhtml\Topic\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;

class Icon extends Generic implements TabInterface
{
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        private readonly \Ronald2Wing\Forum\Model\Icon $iconModel,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm(): Generic
    {
        $model = $this->_coreRegistry->registry('ronald2wing_forum_topic');
        $form = $this->_formFactory->create();

        $fieldset = $form->addFieldset('icon_fieldset', ['legend' => __('Topic Icon')]);

        $fieldset->addField('icon_id', 'radios', [
            'name' => 'icon_id',
            'label' => __('Select Icon'),
            'title' => __('Select Icon'),
            'values' => $this->getIconOptions(),
        ]);

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getTabLabel(): \Magento\Framework\Phrase
    {
        return __('Topic Icon');
    }

    public function getTabTitle(): \Magento\Framework\Phrase
    {
        return __('Topic Icon');
    }

    public function canShowTab(): bool
    {
        return true;
    }

    public function isHidden(): bool
    {
        return false;
    }

    private function getIconOptions(): array
    {
        $options = [['value' => '', 'label' => __('No Icon')]];
        foreach ($this->iconModel->getIconList() as $id => $label) {
            $options[] = ['value' => $id, 'label' => $label];
        }
        return $options;
    }
}
