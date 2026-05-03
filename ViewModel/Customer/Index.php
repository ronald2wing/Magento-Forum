<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Customer;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Ronald2Wing\Forum\Model\Service\UserProfileService;
use Ronald2Wing\Forum\Model\UserSettings;

class Index implements ArgumentInterface
{
    private ?UserSettings $userSettings = null;

    public function __construct(
        private readonly CustomerSession $customerSession,
        private readonly UserProfileService $userProfileService
    ) {}

    public function getCustomerSession(): CustomerSession
    {
        return $this->customerSession;
    }

    public function getUserSettings(): UserSettings
    {
        if ($this->userSettings === null && $this->customerSession->getId()) {
            $this->userSettings = $this->userProfileService->getUserSettings(
                (int) $this->customerSession->getId()
            );
        }
        return $this->userSettings ?? new UserSettings();
    }

    public function getAvatarUrl(): string
    {
        $settings = $this->getUserSettings();
        return $this->userProfileService->getAvatarUrl($settings);
    }

    public function getCustomerName(): string
    {
        if ($this->customerSession->getId()) {
            return $this->userProfileService->getCustomerName((int) $this->customerSession->getId());
        }
        return '';
    }
}
