<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Ui;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Ronald2Wing\Forum\Helper\Constant;

class FormSearch implements ArgumentInterface
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly SessionManagerInterface $forumSession
    ) {}

    public function getSearchAction(): string
    {
        return '';
    }

    public function getNameSearch(): string
    {
        return Constant::SEARCH_QUERY;
    }

    public function getSearchPhrase(): string
    {
        return (string) ($this->request->getParam(Constant::SEARCH_QUERY) ?: $this->forumSession->getData(Constant::SEARCH_SESSION_KEY, ''));
    }

    public function getResetAction(): string
    {
        return '';
    }
}
