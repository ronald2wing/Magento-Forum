<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Plugin\Menu;

use Magento\Backend\Model\Menu;
use Ronald2Wing\Forum\Model\ResourceModel\Post\CollectionFactory;

class AddPendingCount
{
    public function __construct(
        private readonly CollectionFactory $postCollectionFactory
    ) {}

    public function afterGetMenu(Menu\Builder $subject, Menu $menu): Menu
    {
        $pending = $this->postCollectionFactory->create()
            ->addFieldToFilter('status', PostInterface::STATUS_PENDING)->getSize();

        $item = $menu->get('Ronald2Wing_Forum::post_manage');
        if ($item && $pending > 0) {
            $item->setTitle($item->getTitle() . ' (' . $pending . ')');
        }
        return $menu;
    }
}
