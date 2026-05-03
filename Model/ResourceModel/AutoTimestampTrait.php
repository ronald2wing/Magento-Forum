<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;

trait AutoTimestampTrait
{
    protected function beforeSave(AbstractModel $object): void
    {
        if ($object->isObjectNew() && !$object->getCreatedAt()) {
            $object->setCreatedAt(gmdate('Y-m-d H:i:s'));
        }
        $object->setUpdatedAt(gmdate('Y-m-d H:i:s'));
    }
}
