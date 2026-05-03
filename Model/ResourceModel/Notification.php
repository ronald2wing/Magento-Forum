<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Ronald2Wing\Forum\Model\Notification as NotificationModel;

class Notification extends AbstractDb
{
    public const TABLE_NAME = 'forum_notification';

    protected function _construct(): void
    {
        $this->_init(self::TABLE_NAME, 'notification_id');
    }

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object): Notification
    {
        /** @var NotificationModel $object */
        if (!$object->getUnsubscribeHash()) {
            $object->setUnsubscribeHash(bin2hex(random_bytes(16)));
        }
        return parent::_beforeSave($object);
    }
}
