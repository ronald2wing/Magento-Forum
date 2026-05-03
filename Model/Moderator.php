<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model;

use Ronald2Wing\Forum\Api\Data\ModeratorInterface;
use Magento\Framework\Model\AbstractModel;

class Moderator extends AbstractModel implements ModeratorInterface
{
    protected $_eventPrefix = 'ronald2wing_forum_moderator';

    protected function _construct(): void
    {
        $this->_init(\Ronald2Wing\Forum\Model\ResourceModel\Moderator::class);
    }

    public function getId(): ?int
    {
        return $this->getData('moderator_id') !== null ? (int) $this->getData('moderator_id') : null;
    }

    public function setId(?int $id): self
    {
        return $this->setData('moderator_id', $id);
    }

    public function getUserId(): ?int
    {
        return $this->getData('user_id') !== null ? (int) $this->getData('user_id') : null;
    }

    public function setUserId(int $userId): self
    {
        return $this->setData('user_id', $userId);
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
