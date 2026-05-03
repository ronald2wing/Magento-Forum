<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Forum;

use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Helper\Params;
use Ronald2Wing\Forum\Helper\Url as ForumUrl;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Ronald2Wing\Forum\Model\ForumFactory;
use Ronald2Wing\Forum\Model\Service\AuthorisationService;
use Ronald2Wing\Forum\Model\Service\VisitorTracker;

class View implements ActionInterface, HttpGetActionInterface
{
    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly PageFactory $resultPageFactory,
        private readonly RequestInterface $request,
        private readonly Registry $registry,
        private readonly Params $params,
        private readonly ForumFactory $forumFactory,
        private readonly CustomerSession $customerSession,
        private readonly ForumUrl $forumUrl,
        private readonly StoreManagerInterface $storeManager,
        private readonly AuthorisationService $authService,
        private readonly VisitorTracker $visitorTracker,
        private readonly ManagerInterface $messageManager
    ) {}

    public function execute(): ResultInterface
    {
        $forumId = (int) $this->request->getParam(Constant::PARAM_FORUM_ID);
        if (!$forumId) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        $forumModel = $this->forumFactory->create();
        $forumModel->load($forumId);

        $this->visitorTracker->registerVisitation($forumId, 0, 0);
        if (!$forumModel->getId() || $forumModel->getIsDeleted()) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        if (!$this->authService->isAllowed($forumModel)) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)
                ->setUrl($this->forumUrl->getForumUrl());
        }

        $this->registry->register(Constant::REGISTRY_PARENT_FORUM, $forumModel);
        $this->registry->register(
            Constant::PARAM_PAGE,
            $this->params->getPage(Constant::PAGER_TOPIC)
        );
        $this->registry->register(
            Constant::PARAM_LIMIT,
            $this->params->getLimit(Constant::PAGER_TOPIC, Constant::DEFAULT_PAGE_SIZE_TOPIC)
        );
        $this->registry->register(
            Constant::PARAM_SORT,
            $this->params->getSort(Constant::PAGER_TOPIC, Constant::SORT_CREATED_DESC)
        );
        $this->registry->register(Constant::REGISTRY_CUSTOMER_SESSION, $this->customerSession);

        $resultPage = $this->resultPageFactory->create();
        $breadcrumbs = $resultPage->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs) {
            $breadcrumbs->addCrumb('forum_home', [
                'label' => __('Forum'),
                'title' => __('Forum'),
                'link' => '/' . $this->forumUrl->getForumUrl(),
            ]);
            $breadcrumbs->addCrumb('forum_view', [
                'label' => $forumModel->getTitle(),
                'title' => $forumModel->getTitle(),
            ]);
        }

        return $resultPage;
    }
}
