<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Helper;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Session\SessionManagerInterface;

class Params
{
    private const SESSION_KEY_LIMIT = 'forum_limit';
    private const SESSION_KEY_SORT = 'forum_sort';
    private const SESSION_KEY_PAGE = 'forum_page';

    public function __construct(
        private readonly RequestInterface $request,
        private readonly SessionManagerInterface $session
    ) {}

    public function getLimit(string $pagerKey, int $defaultLimit = 10): int
    {
        $sessionKey = self::SESSION_KEY_LIMIT . '_' . $pagerKey;
        $limit = (int) $this->request->getParam(Constant::PARAM_LIMIT);

        if ($limit > 0) {
            $this->session->setData($sessionKey, $limit);
            return $limit;
        }

        return (int) ($this->session->getData($sessionKey) ?: $defaultLimit);
    }

    public function getSort(string $pagerKey, string $defaultSort = Constant::SORT_CREATED_DESC): string
    {
        $sessionKey = self::SESSION_KEY_SORT . '_' . $pagerKey;
        $sort = $this->request->getParam(Constant::PARAM_SORT);

        if ($sort !== null && $sort !== '') {
            $this->session->setData($sessionKey, $sort);
            return $sort;
        }

        return $this->session->getData($sessionKey) ?: $defaultSort;
    }

    public function getPage(string $pagerKey): int
    {
        $sessionKey = self::SESSION_KEY_PAGE . '_' . $pagerKey;
        $page = (int) $this->request->getParam(Constant::PARAM_PAGE);

        if ($page > 0) {
            $this->session->setData($sessionKey, $page);
            return $page;
        }

        return (int) ($this->session->getData($sessionKey) ?: 1);
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
