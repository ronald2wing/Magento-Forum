<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Forum;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Ronald2Wing\Forum\Helper\Data as ForumData;

class TopBlock implements ArgumentInterface
{
    public function __construct(
        private readonly ForumData $forumData
    ) {}

    public function isTopControlsAllowed(): bool
    {
        return $this->forumData->isTopControlsAllowed();
    }
}
