<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class UserSettings extends AbstractDb
{
    public const TABLE_NAME = 'forum_usersettings';

    protected function _construct(): void
    {
        $this->_init(self::TABLE_NAME, 'settings_id');
    }
}
