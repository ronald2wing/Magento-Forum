<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

abstract class AbstractActions extends Column
{
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        protected readonly UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    protected function addAction(array &$item, string $idField, string $editPath, string $deletePath): void
    {
        $title = $item['title'] ?? '';

        $item[$this->getData('name')] = [
            'edit' => [
                'href' => $this->urlBuilder->getUrl($editPath, [$idField => $item[$idField]]),
                'label' => __('Edit'),
            ],
            'delete' => [
                'href' => $this->urlBuilder->getUrl($deletePath, [$idField => $item[$idField]]),
                'label' => __('Delete'),
                'confirm' => [
                    'title' => $title
                        ? __('Delete "%1"', $title)
                        : __('Delete'),
                    'message' => $title
                        ? __('Are you sure you want to delete "%1"?', $title)
                        : __('Are you sure you want to delete this item?'),
                ],
            ],
        ];
    }
}
