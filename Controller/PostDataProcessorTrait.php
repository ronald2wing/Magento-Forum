<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller;

trait PostDataProcessorTrait
{
    private function cleanJs(string $content): string
    {
        $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $content) ?? $content;
        $content = preg_replace('/\bon\w+\s*=\s*"[^"]*"/i', '', $content) ?? $content;
        $content = preg_replace('/\bon\w+\s*=\s*\'[^\']*\'/i', '', $content) ?? $content;
        $content = preg_replace('/javascript\s*:/i', '', $content) ?? $content;
        return $content;
    }
}
