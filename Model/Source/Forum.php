<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Forum implements OptionSourceInterface
{
    public function __construct(
        private readonly \Ronald2Wing\Forum\Model\ResourceModel\Forum\CollectionFactory $forumCollectionFactory
    ) {}

    public function toOptionArray(): array
    {
        $options = [];
        $collection = $this->forumCollectionFactory->create();
        $collection->addFieldToFilter('parent_id', ['null' => true]);

        foreach ($collection as $forum) {
            $options[] = [
                'value' => $forum->getId(),
                'label' => $forum->getTitle(),
            ];
        }

        return $options;
    }
}
