<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Ui\Component\Listing\Column;

class PostActions extends AbstractActions
{
    private const URL_EDIT = 'ronald2wing_forum/post/edit';
    private const URL_DELETE = 'ronald2wing_forum/post/delete';

    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as &$item) {
            if (!isset($item['post_id'])) {
                continue;
            }
            $this->addAction($item, 'post_id', self::URL_EDIT, self::URL_DELETE);
        }

        return $dataSource;
    }
}
