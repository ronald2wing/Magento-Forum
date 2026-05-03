<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\Service;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\NoSuchEntityException;
use Ronald2Wing\Forum\Api\Data\ForumInterface;
use Ronald2Wing\Forum\Api\Data\TopicInterface;
use Ronald2Wing\Forum\Api\Data\PostInterface;
use Ronald2Wing\Forum\Api\ModeratorRepositoryInterface;
use Ronald2Wing\Forum\Model\ForumFactory;

class AuthorisationService
{
    private ?bool $isModerator = null;

    public function __construct(
        private readonly CustomerSession $customerSession,
        private readonly ModeratorRepositoryInterface $moderatorRepository,
        private readonly ForumFactory $forumFactory
    ) {}

    public function isLoggedIn(): bool
    {
        return $this->customerSession->isLoggedIn();
    }

    public function getCustomerId(): ?int
    {
        $id = $this->customerSession->getId();
        return $id ? (int) $id : null;
    }

    public function isModerator(): bool
    {
        if ($this->isModerator === null) {
            $customerId = $this->getCustomerId();
            $this->isModerator = $customerId !== null
                && $this->moderatorRepository->isModerator($customerId);
        }
        return $this->isModerator;
    }

    public function isAllowed(ForumInterface $forum): bool
    {
        if ($this->isModerator()) {
            return true;
        }
        $customerGroupIds = $forum->getCustomerGroups();
        if (!is_array($customerGroupIds) || empty($customerGroupIds)) {
            return true;
        }
        $customerGroupId = (int) $this->customerSession->getCustomerGroupId();
        return in_array($customerGroupId, $customerGroupIds, true);
    }

    public function isOwner(TopicInterface|PostInterface $entity): bool
    {
        $customerId = $this->getCustomerId();
        return $customerId !== null && $entity->getUserId() === $customerId;
    }

    public function canModify(TopicInterface|PostInterface $entity): bool
    {
        return $this->isModerator() || $this->isOwner($entity);
    }

    public function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {
            $this->customerSession->setAfterAuthUrl(
                $this->customerSession->getCurrentUrl() ?? '/'
            );
            $this->customerSession->authenticate();
        }
    }
}
