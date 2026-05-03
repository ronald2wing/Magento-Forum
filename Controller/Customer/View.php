<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Customer;

use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Helper\Url as ForumUrl;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class View implements ActionInterface, HttpGetActionInterface
{
    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly PageFactory $resultPageFactory,
        private readonly RequestInterface $request,
        private readonly CustomerSession $customerSession,
        private readonly Registry $registry,
        private readonly ForumUrl $forumUrl
    ) {}

    public function execute(): ResultInterface
    {
        $userId = $this->request->getParam(Constant::PARAM_USER_ID);
        if (!$userId) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        $this->registry->register(Constant::REGISTRY_USER_ID, $userId);

        $resultPage = $this->resultPageFactory->create();
        $breadcrumbs = $resultPage->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs) {
            $breadcrumbs->addCrumb('forum_home', [
                'label' => __('Forum'),
                'title' => __('Forum'),
                'link' => '/' . $this->forumUrl->getForumUrl(),
            ]);
            $breadcrumbs->addCrumb('forum_user_view', [
                'label' => __('View Forum User'),
                'title' => __('View Forum User'),
            ]);
        }

        return $resultPage;
    }
}
