<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Block\Adminhtml\Topic\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct(): void
    {
        parent::_construct();
        $this->setId('topic_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Topic Information'));
    }
}
