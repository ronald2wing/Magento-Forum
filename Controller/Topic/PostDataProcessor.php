<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Topic;

use Ronald2Wing\Forum\Controller\PostDataProcessorTrait;

class PostDataProcessor
{
    use PostDataProcessorTrait;

    public function filter(array $data): array
    {
        $data['post'] = $this->cleanJs((string) ($data['post'] ?? ''));
        if (!empty($data['title'])) {
            $data['title'] = strip_tags((string) $data['title']);
        }
        if (!empty($data['description'])) {
            $data['description'] = strip_tags((string) $data['description']);
        }
        $data['title'] = empty($data['title']) ? '-TITLE-NOT-ADDED' : $data['title'];
        return $data;
    }
}
