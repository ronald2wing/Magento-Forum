<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\ViewModel\Customer;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Model\Service\UserProfileService;
use Ronald2Wing\Forum\Model\UserSettings;

class View implements ArgumentInterface
{
    private ?int $userId = null;
    private ?UserSettings $userSettings = null;

    public function __construct(
        private readonly RequestInterface $request,
        private readonly UserProfileService $userProfileService
    ) {}

    public function getUserId(): ?int
    {
        if ($this->userId === null) {
            $userId = $this->request->getParam(Constant::PARAM_USER_ID);
            $this->userId = $userId ? (int) $userId : null;
        }
        return $this->userId;
    }

    public function getCustomerName(): string
    {
        $userId = $this->getUserId();
        if (!$userId) {
            return '';
        }
        return $this->userProfileService->getCustomerName($userId);
    }

    public function getAvatarUrl(): string
    {
        $settings = $this->getUserSettings();
        return $this->userProfileService->getAvatarUrl($settings);
    }

    public function getRole(): string
    {
        $userId = $this->getUserId();
        if (!$userId) {
            return 'User';
        }
        return $this->userProfileService->getRole($userId);
    }

    public function getTotalTopics(): int
    {
        $userId = $this->getUserId();
        if (!$userId) {
            return 0;
        }
        return $this->userProfileService->getTotalTopics($userId);
    }

    public function getTotalPosts(): int
    {
        $userId = $this->getUserId();
        if (!$userId) {
            return 0;
        }
        return $this->userProfileService->getTotalPosts($userId);
    }

    public function getUserSettings(): UserSettings
    {
        if ($this->userSettings === null) {
            $userId = $this->getUserId();
            $this->userSettings = $userId
                ? $this->userProfileService->getUserSettings($userId)
                : new UserSettings();
        }
        return $this->userSettings;
    }
}
