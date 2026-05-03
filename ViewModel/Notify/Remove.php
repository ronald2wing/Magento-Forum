<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Notify;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Remove implements ArgumentInterface
{
    public function __construct(
        private readonly Registry $coreRegistry
    ) {}

    public function isSuccess(): bool
    {
        return (bool) $this->coreRegistry->registry('forum_notify_remove_success');
    }
}
