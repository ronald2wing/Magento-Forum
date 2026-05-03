<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Block\Adminhtml\Adminsettings;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;

class Edit extends Container
{
    public function __construct(
        Context $context,
        private readonly Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    protected function _construct(): void
    {
        $this->_blockGroup = 'Ronald2Wing_Forum';
        $this->_controller = 'adminhtml_adminsettings';
        parent::_construct();
        $this->buttonList->update('save', 'label', __('Save Settings'));
        $this->buttonList->remove('delete');
    }

    public function getHeaderText(): \Magento\Framework\Phrase
    {
        return __('Admin Settings');
    }
}
