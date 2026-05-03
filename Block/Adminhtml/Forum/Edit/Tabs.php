<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Block\Adminhtml\Forum\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct(): void
    {
        parent::_construct();
        $this->setId('forum_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Forum Information'));
    }
}
