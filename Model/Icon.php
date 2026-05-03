<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model;

use Magento\Store\Model\StoreManagerInterface;

class Icon
{
    public const ICON_PATH = 'ronald2wingforum/icons/';

    private array $iconList = [
        'smile' => 'Smile',
        'wink' => 'Wink',
        'kiss' => 'Kiss',
        'saint' => 'Saint',
        'evil' => 'Evil',
        'question' => 'Question',
        'cry' => 'Cry',
        'devil' => 'Devil',
        'shame' => 'Shame',
        'disappointed' => 'Disappointed',
        'smart' => 'Smart',
        'laughter' => 'Laughter',
        'monkey' => 'Monkey',
        'sad' => 'Sad',
        'accept' => 'Accept',
        'cancel' => 'Cancel',
    ];

    public function getIconList(): array
    {
        return $this->iconList;
    }

    public function getIconIds(): array
    {
        return array_keys($this->iconList);
    }

    public function getIconLabel(string $iconId): string
    {
        return $this->iconList[$iconId] ?? '';
    }

    public function getIconUrl(string $iconId, StoreManagerInterface $storeManager): string
    {
        return $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
            . self::ICON_PATH . $iconId . '.png';
    }

    public function getAllIconsData(StoreManagerInterface $storeManager): array
    {
        $result = ['ids' => $this->getIconIds(), 'labels' => [], 'images' => []];

        foreach ($this->iconList as $id => $label) {
            $result['labels'][] = $label;
            $result['images'][] = $this->getIconUrl($id, $storeManager);
        }

        return $result;
    }
}
