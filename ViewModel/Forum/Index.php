<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Forum;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;
use Ronald2Wing\Forum\Helper\Data as ForumData;
use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Model\ResourceModel\Forum\Collection as ForumCollection;
use Ronald2Wing\Forum\Model\ResourceModel\Forum\CollectionFactory as ForumCollectionFactory;
use Ronald2Wing\Forum\Model\Service\AuthorisationService;
use Magento\Framework\Exception\NoSuchEntityException;

class Index implements ArgumentInterface
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly ForumData $forumData,
        private readonly ForumCollectionFactory $forumCollectionFactory,
        private readonly AuthorisationService $authService,
        private readonly CustomerSession $customerSession,
        private readonly StoreManagerInterface $storeManager
    ) {}

    public function getIsModerator(): bool
    {
        return $this->authService->isModerator();
    }

    public function formatDateTime(string $dateTime): string
    {
        return $this->forumData->formatDateTime($dateTime);
    }

    public function getAllForums(): ForumCollection
    {
        return $this->forumCollectionFactory->create()
            ->enabledOnly()
            ->addStoreFilterToCollection((int) $this->storeManager->getStore()->getId())
            ->setOrder($this->getSortField(), $this->getSort())
            ->setCurPage($this->getPageNum());
    }

    public function getSort(): string
    {
        return $this->request->getParam(Constant::PARAM_SORT)
            ?? Constant::SORT_CREATED_DESC;
    }

    public function getSortField(): string
    {
        $type = $this->getSort();
        if (in_array($type, [Constant::SORT_TITLE_ASC, Constant::SORT_TITLE_DESC], true)) {
            return 'title';
        }
        if (in_array($type, [Constant::SORT_POSTS_ASC, Constant::SORT_POSTS_DESC], true)) {
            return 'total_posts';
        }
        return 'created_at';
    }

    public function getPageNum(): int
    {
        return (int) $this->request->getParam(Constant::PARAM_PAGE, 1);
    }

    public function getLimit(): int
    {
        return (int) $this->request->getParam(Constant::PARAM_LIMIT, Constant::DEFAULT_PAGE_SIZE_FORUM);
    }
}
