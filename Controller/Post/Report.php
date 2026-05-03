<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Post;

use Ronald2Wing\Forum\Api\Data\ReportInterface;
use Ronald2Wing\Forum\Helper\Constant;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Message\ManagerInterface;
use Ronald2Wing\Forum\Model\PostFactory;
use Ronald2Wing\Forum\Model\ReportFactory;

class Report implements ActionInterface, HttpPostActionInterface
{
    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly RequestInterface $request,
        private readonly CustomerSession $customerSession,
        private readonly PostFactory $postFactory,
        private readonly ReportFactory $reportFactory,
        private readonly FormKeyValidator $formKeyValidator,
        private readonly ManagerInterface $messageManager
    ) {}

    public function execute(): ResultInterface
    {
        if (!$this->customerSession->isLoggedIn()) {
            $this->customerSession->setAfterAuthUrl('/');
            $this->customerSession->authenticate();
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        if (!$this->formKeyValidator->validate($this->request)) {
            $this->messageManager->addError(__('Invalid form key. Please refresh the page.'));
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        $postId = (int) $this->request->getParam(Constant::PARAM_POST_ID);
        if (!$postId) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        $post = $this->postFactory->create();
        $post->load($postId);

        if (!$post->getId() || $post->getIsDeleted()) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/');
        }

        $report = $this->reportFactory->create();
        $report->setPostId($postId);
        $report->setUserId((int) $this->customerSession->getId());
        $report->setStatus(ReportInterface::STATUS_NEW);
        $report->save();

        $this->messageManager->addSuccess(__('Post has been reported. A moderator will review it.'));

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)
            ->setUrl($this->request->getServer('HTTP_REFERER', '/'));
    }
}
