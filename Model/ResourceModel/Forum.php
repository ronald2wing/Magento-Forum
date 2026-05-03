<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\AbstractModel;
use Ronald2Wing\Forum\Model\Forum as ForumModel;

class Forum extends AbstractDb
{
    use AutoTimestampTrait;

    public const TABLE_FORUM = 'forum';
    public const TABLE_ACCESS = 'forum_access';

    public function __construct(Context $context, ?string $connectionName = null)
    {
        parent::__construct($context, $connectionName);
    }

    protected function _construct(): void
    {
        $this->_init(self::TABLE_FORUM, 'forum_id');
    }

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object): Forum
    {
        $this->beforeSave($object);

        return parent::_beforeSave($object);
    }

    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object): Forum
    {
        /** @var ForumModel $object */
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getTable(self::TABLE_ACCESS), ['customer_group_id'])
            ->where('forum_id = ?', $object->getId());
        $groups = $connection->fetchCol($select);
        $object->setCustomerGroups($groups);

        return parent::_afterLoad($object);
    }

    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object): Forum
    {
        /** @var ForumModel $object */
        $connection = $this->getConnection();
        $table = $this->getTable(self::TABLE_ACCESS);
        $forumId = $object->getId();

        $connection->delete($table, ['forum_id = ?' => $forumId]);

        $groups = $object->getCustomerGroups();
        if (is_array($groups) && !empty($groups)) {
            $data = [];
            foreach ($groups as $groupId) {
                $data[] = ['forum_id' => $forumId, 'customer_group_id' => $groupId];
            }
            $connection->insertMultiple($table, $data);
        }

        return parent::_afterSave($object);
    }
}
