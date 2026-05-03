<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model;

use Magento\Framework\Model\AbstractModel;

class Visitor extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(\Ronald2Wing\Forum\Model\ResourceModel\Visitor::class);
    }

    public function getId(): ?int
    {
        return $this->getData('visitor_id') !== null ? (int) $this->getData('visitor_id') : null;
    }

    public function setId(?int $id): self
    {
        return $this->setData('visitor_id', $id);
    }
}
