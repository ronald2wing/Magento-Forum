<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Ronald2Wing\Forum\Model\Post as PostModel;

class Post extends AbstractDb
{
    use AutoTimestampTrait;

    public const TABLE_NAME = 'forum_post';

    public function __construct(
        Context $context,
        ?string $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    protected function _construct(): void
    {
        $this->_init(self::TABLE_NAME, 'post_id');
    }

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object): Post
    {
        $this->beforeSave($object);

        return parent::_beforeSave($object);
    }

    public function getParentsId(int $postId): int
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), ['forum_id'])
            ->where('post_id = ?', $postId);

        return (int) $connection->fetchOne($select);
    }
}
