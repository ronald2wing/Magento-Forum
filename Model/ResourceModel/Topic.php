<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Ronald2Wing\Forum\Model\Topic as TopicModel;

class Topic extends AbstractDb
{
    use AutoTimestampTrait;

    public const TABLE_NAME = 'forum_topic';

    public function __construct(
        Context $context,
        ?string $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    protected function _construct(): void
    {
        $this->_init(self::TABLE_NAME, 'topic_id');
    }

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object): Topic
    {
        $this->beforeSave($object);

        return parent::_beforeSave($object);
    }
}
