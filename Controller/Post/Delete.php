<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Post;

use Ronald2Wing\Forum\Helper\Constant;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Ronald2Wing\Forum\Model\PostFactory;
use Ronald2Wing\Forum\Model\Service\AuthorisationService;
use Ronald2Wing\Forum\Model\Service\CounterUpdater;

class Delete implements ActionInterface, HttpGetActionInterface
{
    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly RequestInterface $request,
        private readonly CustomerSession $customerSession,
        private readonly PostFactory $postFactory,
        private readonly AuthorisationService $authService,
        private readonly CounterUpdater $counterUpdater,
        private readonly ManagerInterface $messageManager
    ) {}

    public function execute(): ResultInterface
    {
        if (!$this->customerSession->isLoggedIn()) {
            $this->customerSession->setAfterAuthUrl('/');
            $this->customerSession->authenticate();
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        $postId = (int) $this->request->getParam(Constant::PARAM_POST_ID);
        $post = $this->postFactory->create();
        $post->load($postId);

        if (!$post->getStatus() || $post->getIsDeleted() || !$this->authService->canModify($post)) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        $post->setIsDeleted(true);
        $post->save();

        $this->counterUpdater->updateTopicCounts($post->getTopicId());
        $this->counterUpdater->updateForumCounts($post->getForumId());

        $this->messageManager->addSuccess(__('You successfully deleted post'));

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
    }
}
