<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Block\Adminhtml\Post\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct(): void
    {
        parent::_construct();
        $this->setId('post_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Post Information'));
    }
}
