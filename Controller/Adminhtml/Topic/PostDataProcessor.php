<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Adminhtml\Topic;

class PostDataProcessor
{
    public function filter(array $data): array
    {
        if (!empty($data['icon_id']) && !empty($data['icon_id'][0])) {
            $data['icon_id'] = $data['icon_id'][0];
        } else {
            $data['icon_id'] = '';
        }
        if (!empty($data['title'])) {
            $data['title'] = strip_tags((string) $data['title']);
        }
        return $data;
    }

    public function validate(array $data): bool
    {
        return true;
    }
}
