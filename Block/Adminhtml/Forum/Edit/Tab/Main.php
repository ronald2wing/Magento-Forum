<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Block\Adminhtml\Forum\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Customer\Model\ResourceModel\Group\Collection as CustomerGroupCollection;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;

class Main extends Generic implements TabInterface
{
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        private readonly Store $systemStore,
        private readonly CustomerGroupCollection $customerGroup,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm(): Generic
    {
        $model = $this->_coreRegistry->registry('ronald2wing_forum_forum');

        $isElementDisabled = !$this->_isAllowedAction('Ronald2Wing_Forum::forum_save');

        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('forum_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Forum Information')]);

        if ($model->getId()) {
            $fieldset->addField('forum_id', 'hidden', ['name' => 'forum_id']);
        }

        $fieldset->addField('title', 'text', [
            'name' => 'title',
            'label' => __('Forum Name'),
            'title' => __('Forum Name'),
            'required' => true,
            'disabled' => $isElementDisabled,
        ]);

        $fieldset->addField('url_key', 'text', [
            'name' => 'url_key',
            'label' => __('URL Key'),
            'title' => __('URL Key'),
            'class' => 'validate-identifier',
            'note' => __('Relative to Web Site Base URL and forum route'),
            'disabled' => $isElementDisabled,
        ]);

        $fieldset->addField('customer_groups', 'multiselect', [
            'name' => 'customer_groups',
            'label' => __('Access Groups'),
            'title' => __('Access Groups'),
            'values' => $this->getCustomersGroups(),
            'disabled' => $isElementDisabled,
            'note' => __('Leave empty for all groups'),
        ]);

        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField('store_id', 'select', [
                'name' => 'store_id',
                'label' => __('Store View'),
                'title' => __('Store View'),
                'required' => true,
                'values' => $this->systemStore->getStoreValuesForForm(false, true),
                'disabled' => $isElementDisabled,
            ]);
            $renderer = $this->getLayout()->createBlock(
                \Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element::class
            );
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField('store_id', 'hidden', [
                'name' => 'stores[]',
                'value' => $this->_storeManager->getStore(true)->getId(),
            ]);
            $model->setStoreId($this->_storeManager->getStore(true)->getId());
        }

        $fieldset->addField('status', 'select', [
            'label' => __('Status'),
            'title' => __('Forum Status'),
            'name' => 'status',
            'required' => true,
            'options' => ['1' => __('Enabled'), '0' => __('Disabled')],
            'disabled' => $isElementDisabled,
        ]);

        $fieldset->addField('description', 'textarea', [
            'label' => __('Description'),
            'title' => __('Forum Description'),
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
        return __('Forum Information');
    }

    public function getTabTitle(): \Magento\Framework\Phrase
    {
        return __('Forum Information');
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

    private function getCustomersGroups(): array
    {
        return $this->customerGroup->toOptionArray();
    }
}
