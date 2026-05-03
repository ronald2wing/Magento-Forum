<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\Service;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Ronald2Wing\Forum\Helper\Data as DataHelper;
use Ronald2Wing\Forum\Model\NotificationFactory;
use Ronald2Wing\Forum\Model\ResourceModel\Notification as NotificationResource;
use Ronald2Wing\Forum\Model\ResourceModel\Notification\CollectionFactory as NotificationCollectionFactory;

class NotificationService
{
    private const XML_PATH_EMAIL_TEMPLATE = 'ronald2wing_forum_notify_customer';

    public function __construct(
        private readonly NotificationFactory $notificationFactory,
        private readonly NotificationResource $notificationResource,
        private readonly NotificationCollectionFactory $notificationCollectionFactory,
        private readonly TransportBuilder $transportBuilder,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly StoreManagerInterface $storeManager,
        private readonly DataHelper $dataHelper,
        private readonly LoggerInterface $logger
    ) {}

    public function subscribe(int $userId, int $topicId): string
    {
        $collection = $this->notificationCollectionFactory->create();
        $collection->addFieldToFilter('user_id', $userId);
        $collection->addFieldToFilter('topic_id', $topicId);

        if ($collection->getSize() > 0) {
            $notification = $collection->getFirstItem();
        } else {
            $notification = $this->notificationFactory->create();
        }

        $hash = bin2hex(random_bytes(16));
        $notification->setUserId($userId);
        $notification->setTopicId($topicId);
        $notification->setUnsubscribeHash($hash);
        $this->notificationResource->save($notification);

        return $hash;
    }

    public function unsubscribeByHash(string $hash): bool
    {
        $collection = $this->notificationCollectionFactory->create();
        $collection->addFieldToFilter('unsubscribe_hash', $hash);

        if ($collection->getSize() === 0) {
            return false;
        }

        foreach ($collection as $notification) {
            $this->notificationResource->delete($notification);
        }

        return true;
    }

    public function sendNotification(int $topicId, string $topicTitle, string $topicUrl, string $postPreview): void
    {
        $collection = $this->notificationCollectionFactory->create();
        $collection->addFieldToFilter('topic_id', $topicId);

        foreach ($collection as $notification) {
            try {
                $userId = $notification->getUserId();
                if (!$userId) {
                    continue;
                }

                $customer = $this->customerRepository->getById($userId);

                $transport = $this->transportBuilder
                    ->setTemplateIdentifier(self::XML_PATH_EMAIL_TEMPLATE)
                    ->setTemplateOptions([
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => $this->storeManager->getStore()->getId(),
                    ])
                    ->setTemplateVars([
                        'customer_name' => $customer->getFirstname(),
                        'topic_title' => $topicTitle,
                        'topic_url' => $topicUrl,
                        'post_preview' => $postPreview,
                        'unsubscribe_url' => $this->getUnsubscribeUrl($notification->getUnsubscribeHash() ?? ''),
                    ])
                    ->setFromByScope([
                        'email' => $this->dataHelper->getSenderEmail(),
                        'name' => $this->dataHelper->getSenderName(),
                    ])
                    ->addTo($customer->getEmail(), $customer->getFirstname() . ' ' . $customer->getLastname())
                    ->getTransport();

                $transport->sendMessage();
            } catch (\Exception $e) {
                $this->logger->error('Failed to send notification', ['exception' => $e]);
                continue;
            }
        }
    }

    private function getUnsubscribeUrl(string $hash): string
    {
        $baseUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_LINK);

        return $baseUrl . $this->dataHelper->getRoute() . '/notify/remove/hash/' . $hash . '/';
    }
}
