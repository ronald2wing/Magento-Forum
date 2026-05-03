<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Ui;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Ronald2Wing\Forum\Helper\Url as UrlHelper;

class Rss implements ArgumentInterface
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly UrlHelper $urlHelper
    ) {}

    public function getLinkUrl(): string
    {
        $params = [];
        $topicId = (int) $this->request->getParam('topic_id');
        $forumId = (int) $this->request->getParam('id');
        if ($topicId) { $params['topic_id'] = $topicId; }
        if ($forumId) { $params['id'] = $forumId; }

        $url = $this->urlHelper->getRssUrl();
        return $params ? $url . '?' . http_build_query($params) : $url;
    }
}
