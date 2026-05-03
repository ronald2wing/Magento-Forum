<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Adminhtml\Forum;

class PostDataProcessor
{
    public function filter(array $data): array
    {
        if (!empty($data['icon_id']) && !empty($data['icon_id'][0])) {
            $data['icon_id'] = $data['icon_id'][0];
        } else {
            $data['icon_id'] = '';
        }
        return $data;
    }

    public function validate(array $data): bool
    {
        return true;
    }
}
