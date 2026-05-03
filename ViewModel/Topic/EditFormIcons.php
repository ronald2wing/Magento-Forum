<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Topic;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Ronald2Wing\Forum\Model\Icon;
use Magento\Store\Model\StoreManagerInterface;

class EditFormIcons implements ArgumentInterface
{
    public function __construct(
        private readonly Icon $icon,
        private readonly StoreManagerInterface $storeManager
    ) {}

    public function getAllIcons(): array
    {
        return $this->icon->getAllIconsData($this->storeManager);
    }
}
