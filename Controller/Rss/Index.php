<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Rss;

use Ronald2Wing\Forum\Helper\Constant;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Ronald2Wing\Forum\Model\ForumFactory;
use Ronald2Wing\Forum\Model\TopicFactory;

class Index implements ActionInterface, HttpGetActionInterface
{
    public function __construct(
        private readonly PageFactory $resultPageFactory,
        private readonly RawFactory $resultRawFactory,
        private readonly RequestInterface $request,
        private readonly ResponseInterface $response,
        private readonly StoreManagerInterface $storeManager,
        private readonly ForumFactory $forumFactory,
        private readonly TopicFactory $topicFactory,
        private readonly Registry $registry
    ) {}

    public function execute(): ResultInterface
    {
        $topicId = (int) $this->request->getParam(Constant::PARAM_TOPIC_ID);

        if ($topicId) {
            $topicModel = $this->topicFactory->create();
            $topicModel->load($topicId);

            if ($topicModel->getId() && !$topicModel->getIsDeleted() && $topicModel->getStatus()) {
                $this->registry->register(Constant::REGISTRY_RSS_TOPIC, $topicModel);
            }
        } else {
            $forumCollection = $this->forumFactory->create()->getCollection()
                ->enabledOnly()
                ->addStoreFilterToCollection((int) $this->storeManager->getStore()->getId());

            $forumId = (int) $this->request->getParam(Constant::RSS_FORUM_ID);
            if ($forumId) {
                $forumCollection->addFieldToFilter('forum_id', $forumId);
            }

            $this->registry->register(Constant::REGISTRY_RSS_FORUMS, $forumCollection);
        }

        $resultPage = $this->resultPageFactory->create();

        $this->response->setHeader('Content-type', 'text/xml; charset=UTF-8');

        $resultRaw = $this->resultRawFactory->create();
        $block = $resultPage->getLayout()->getBlock('forum.rss.feed');
        $resultRaw->setContents($block ? (string) $block->toHtml() : '');

        return $resultRaw;
    }
}
