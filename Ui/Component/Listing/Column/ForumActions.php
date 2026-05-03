<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Ui\Component\Listing\Column;

class ForumActions extends AbstractActions
{
    private const URL_EDIT = 'ronald2wing_forum/forum/edit';
    private const URL_DELETE = 'ronald2wing_forum/forum/delete';

    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as &$item) {
            if (!isset($item['forum_id'])) {
                continue;
            }
            $this->addAction($item, 'forum_id', self::URL_EDIT, self::URL_DELETE);
        }

        return $dataSource;
    }
}
