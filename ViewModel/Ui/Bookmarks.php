<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Ui;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Ronald2Wing\Forum\Helper\Constant;

class Bookmarks implements ArgumentInterface
{
    public function __construct(
        private readonly RequestInterface $request
    ) {}

    public function getActionForm(): string
    {
        return '';
    }

    public function getTopicId(): int
    {
        return (int) $this->request->getParam(Constant::PARAM_TOPIC_ID);
    }

    public function getIsShowAddButton(): bool
    {
        return $this->getTopicId() > 0;
    }
}
