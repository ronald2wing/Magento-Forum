<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Adminhtml\Post;

use Ronald2Wing\Forum\Controller\PostDataProcessorTrait;

class PostDataProcessor
{
    use PostDataProcessorTrait;

    public function filter(array $data): array
    {
        if (!empty($data['content'])) {
            $data['content'] = $this->cleanJs((string) $data['content']);
        }
        return $data;
    }

    public function validate(array $data): bool
    {
        return true;
    }
}
