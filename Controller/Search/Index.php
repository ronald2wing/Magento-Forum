<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Search;

use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Helper\Params;
use Ronald2Wing\Forum\Helper\Url as ForumUrl;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Index implements ActionInterface, HttpGetActionInterface
{
    private string $type = Constant::SEARCH_TYPE_POST;

    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly PageFactory $resultPageFactory,
        private readonly RequestInterface $request,
        private readonly ForumUrl $forumUrl,
        private readonly Params $params,
        private readonly Registry $registry,
        private readonly SessionManagerInterface $forumSession
    ) {}

    public function execute(): ResultInterface
    {
        $search = $this->request->getParam(Constant::SEARCH_QUERY)
            ? strip_tags((string) $this->request->getParam(Constant::SEARCH_QUERY))
            : $this->forumSession->getData(Constant::REGISTRY_SEARCH_QUERY);

        if (!$search) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)
                ->setUrl($this->forumUrl->getForumUrl());
        }

        $this->forumSession->setData(Constant::REGISTRY_SEARCH_QUERY, $search);
        $this->registry->register(Constant::REGISTRY_SEARCH_QUERY, $search);

        $this->registerSearchType();
        $this->registerPageData();

        $resultPage = $this->resultPageFactory->create();
        $breadcrumbs = $resultPage->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs) {
            $breadcrumbs->addCrumb('forum_home', [
                'label' => __('Forum'),
                'title' => __('Forum'),
                'link' => '/' . $this->forumUrl->getForumUrl(),
            ]);
            $breadcrumbs->addCrumb('forum_search', [
                'label' => __('Search Forum: "%1"', $search),
                'title' => __('Search Forum: "%1"', $search),
            ]);
        }

        return $resultPage;
    }

    private function registerSearchType(): void
    {
        $type = strip_tags((string) $this->request->getParam(Constant::SEARCH_TYPE));
        if (!$type) {
            $type = $this->forumSession->getData(Constant::REGISTRY_SEARCH_TYPE);
        }

        if ($type !== Constant::SEARCH_TYPE_POST && $type !== Constant::SEARCH_TYPE_TOPIC) {
            $type = Constant::SEARCH_TYPE_POST;
        }

        $this->forumSession->setData(Constant::REGISTRY_SEARCH_TYPE, $type);
        $this->registry->register(Constant::REGISTRY_SEARCH_TYPE, $type);
        $this->type = $type;
    }

    private function registerPageData(): void
    {
        $isPost = $this->type === Constant::SEARCH_TYPE_POST;
        $pagerKey = $isPost ? Constant::PAGER_SEARCH_POST : Constant::PAGER_SEARCH_TOPIC;

        $this->registry->register(
            $pagerKey,
            $this->params->getPage($pagerKey)
        );
        $pagerLimitKey = $isPost ? Constant::PAGER_SEARCH_POST . '_limit' : Constant::PAGER_SEARCH_TOPIC . '_limit';
        $this->registry->register(
            $pagerLimitKey,
            $this->params->getLimit($pagerKey, Constant::DEFAULT_PAGE_SIZE_POST)
        );
        $pagerSortKey = $isPost ? Constant::PAGER_SEARCH_POST . '_sort' : Constant::PAGER_SEARCH_TOPIC . '_sort';
        $this->registry->register(
            $pagerSortKey,
            $this->params->getSort($pagerKey, Constant::SORT_CREATED_DESC)
        );
    }
}
