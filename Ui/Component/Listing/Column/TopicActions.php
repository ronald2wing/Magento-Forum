<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Ui\Component\Listing\Column;

class TopicActions extends AbstractActions
{
    private const URL_EDIT = 'ronald2wing_forum/topic/edit';
    private const URL_DELETE = 'ronald2wing_forum/topic/delete';

    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as &$item) {
            if (!isset($item['topic_id'])) {
                continue;
            }
            $this->addAction($item, 'topic_id', self::URL_EDIT, self::URL_DELETE);
        }

        return $dataSource;
    }
}
