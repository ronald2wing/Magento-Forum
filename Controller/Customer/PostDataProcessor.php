<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Customer;

use Ronald2Wing\Forum\Controller\PostDataProcessorTrait;

class PostDataProcessor
{
    use PostDataProcessorTrait;

    public function filter(array $data): array
    {
        if (!empty($data['nickname'])) {
            $data['nickname'] = strip_tags((string) $data['nickname']);
        }
        if (!empty($data['signature'])) {
            $data['signature'] = strip_tags((string) $data['signature'], '<br><em><i><b>');
            $data['signature'] = $this->cleanJs($data['signature']);
        }
        return $data;
    }
}
