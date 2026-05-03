<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Layout implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            ['value' => '1column', 'label' => __('1 Column')],
            ['value' => '2columns-left', 'label' => __('2 Columns Left')],
            ['value' => '2columns-right', 'label' => __('2 Columns Right')],
        ];
    }
}
