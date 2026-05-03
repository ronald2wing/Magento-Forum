<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model;

use Magento\Framework\Model\AbstractModel;

class UserSettings extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(\Ronald2Wing\Forum\Model\ResourceModel\UserSettings::class);
    }

    public function getId(): ?int
    {
        return $this->getData('settings_id') !== null ? (int) $this->getData('settings_id') : null;
    }

    public function setId(?int $id): self
    {
        return $this->setData('settings_id', $id);
    }

    public function getUserId(): ?int
    {
        return $this->getData('user_id') !== null ? (int) $this->getData('user_id') : null;
    }

    public function setUserId(int $userId): self
    {
        return $this->setData('user_id', $userId);
    }

    public function getNickname(): ?string
    {
        return $this->getData('nickname');
    }

    public function setNickname(?string $nickname): self
    {
        return $this->setData('nickname', $nickname);
    }

    public function getSignature(): ?string
    {
        return $this->getData('signature');
    }

    public function setSignature(?string $signature): self
    {
        return $this->setData('signature', $signature);
    }

    public function getAvatar(): ?string
    {
        return $this->getData('avatar');
    }

    public function setAvatar(?string $avatar): self
    {
        return $this->setData('avatar', $avatar);
    }

    public function getWebsiteId(): ?int
    {
        return $this->getData('website_id') !== null ? (int) $this->getData('website_id') : null;
    }

    public function setWebsiteId(?int $websiteId): self
    {
        return $this->setData('website_id', $websiteId);
    }
}
