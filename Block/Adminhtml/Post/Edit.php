<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Block\Adminhtml\Post;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container;
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
        $this->_objectId = 'post_id';
        $this->_blockGroup = 'Ronald2Wing_Forum';
        $this->_controller = 'adminhtml_post';

        parent::_construct();

        if ($this->_isAllowedAction('Ronald2Wing_Forum::post_save')) {
            $this->buttonList->update('save', 'label', __('Save Post'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ],
                ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
        }

        if ($this->_isAllowedAction('Ronald2Wing_Forum::post_delete')) {
            $this->buttonList->update('delete', 'label', __('Delete Post'));
        } else {
            $this->buttonList->remove('delete');
        }
    }

    public function getHeaderText(): \Magento\Framework\Phrase
    {
        $model = $this->registry->registry('ronald2wing_forum_post');
        if ($model && $model->getId()) {
            return __('Edit Post');
        }
        return __('New Post');
    }

    protected function _isAllowedAction(string $resourceId): bool
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    protected function _getSaveAndContinueUrl(): string
    {
        return $this->getUrl('ronald2wing_forum/*/save', [
            '_current' => true,
            'back' => 'edit',
            'active_tab' => '{{tab_id}}',
        ]);
    }
}
