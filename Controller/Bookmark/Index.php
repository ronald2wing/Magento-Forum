<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Bookmark;

use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Helper\Url as ForumUrl;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Index implements ActionInterface, HttpGetActionInterface
{
    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly PageFactory $resultPageFactory,
        private readonly RequestInterface $request,
        private readonly Registry $registry,
        private readonly ForumUrl $forumUrl
    ) {}

    public function execute(): ResultInterface
    {
        $bookmarkIds = $this->request->getParam(Constant::BOOKMARK_TOPIC_IDS);
        $this->registry->register(Constant::REGISTRY_BOOKMARKS, $bookmarkIds);

        $resultPage = $this->resultPageFactory->create();
        $breadcrumbs = $resultPage->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs) {
            $breadcrumbs->addCrumb('forum_home', [
                'label' => __('Forum'),
                'title' => __('Forum'),
                'link' => '/' . $this->forumUrl->getForumUrl(),
            ]);
            $breadcrumbs->addCrumb('forum_bookmarks', [
                'label' => __('Bookmarks'),
                'title' => __('Bookmarks'),
            ]);
        }

        return $resultPage;
    }
}
