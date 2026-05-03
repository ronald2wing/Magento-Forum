<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Block\Adminhtml\Adminsettings\Edit;

use Magento\Backend\Block\Widget\Form\Generic;

class Form extends Generic
{
    protected function _prepareForm(): Generic
    {
        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'edit_form',
                'action' => $this->getData('action'),
                'method' => 'post',
            ],
        ]);
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
