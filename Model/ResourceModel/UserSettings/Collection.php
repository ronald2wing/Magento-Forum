<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\ResourceModel\UserSettings;

use Ronald2Wing\Forum\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'settings_id';
    protected $_eventPrefix = 'ronald2wing_forum_usersettings_collection';
    protected $_eventObject = 'usersettings_collection';

    protected function _construct(): void
    {
        $this->_init(
            \Ronald2Wing\Forum\Model\UserSettings::class,
            \Ronald2Wing\Forum\Model\ResourceModel\UserSettings::class
        );
    }
}
