<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller\Customer;

use Ronald2Wing\Forum\Helper\Constant;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Psr\Log\LoggerInterface;
use Ronald2Wing\Forum\Model\UserSettingsFactory;

class Save implements ActionInterface, HttpPostActionInterface
{
    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly RequestInterface $request,
        private readonly CustomerSession $customerSession,
        private readonly AdapterFactory $adapterFactory,
        private readonly UploaderFactory $uploaderFactory,
        private readonly PostDataProcessor $dataProcessor,
        private readonly UserSettingsFactory $forumUserSettingsFactory,
        private readonly Filesystem $filesystem,
        private readonly FormKeyValidator $formKeyValidator,
        private readonly ManagerInterface $messageManager,
        private readonly LoggerInterface $logger
    ) {}

    public function execute(): ResultInterface
    {
        if (!$this->customerSession->isLoggedIn()) {
            $this->customerSession->setAfterAuthUrl('/');
            $this->customerSession->authenticate();
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('*/*');
        }

        if (!$this->formKeyValidator->validate($this->request)) {
            $this->messageManager->addError(__('Invalid form key. Please refresh the page.'));
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('*/*');
        }

        $post = $this->request->getPostValue();
        if (!$post) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('*/*');
        }

        $data = $this->dataProcessor->filter(is_array($post) ? $post : []);

        $forumUserModel = $this->forumUserSettingsFactory->create();
        $forumUserModel->load((int) $this->customerSession->getId(), 'user_id');

        $forumUserModel->setSignature((string) ($data['signature'] ?? ''));
        $forumUserModel->setNickname((string) ($data['nickname'] ?? ''));
        $forumUserModel->setUserId((int) $this->customerSession->getId());

        $avatarNew = $this->saveAvatar();
        if ($avatarNew) {
            $forumUserModel->setAvatar($avatarNew);
        } elseif (!empty($data['avatar']['delete'])) {
            $forumUserModel->setAvatar(null);
        }

        $forumUserModel->save();
        $this->messageManager->addSuccess(__('You successfully updated your forum settings'));

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('*/*');
    }

    private function saveAvatar(): string|false
    {
        $files = $this->request->getFiles();
        if (empty($files['avatar']['name'])) {
        }

        try {
            $baseMediaPath = Constant::AVATAR_PATH;
            $uploader = $this->uploaderFactory->create(['fileId' => 'avatar']);
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
            $imageAdapter = $this->adapterFactory->create();
            $uploader->addValidateCallback('avatar', $imageAdapter, 'validateUploadFile');
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);

            $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
            $result = $uploader->save(
                $mediaDirectory->getAbsolutePath($baseMediaPath)
            );

            return $baseMediaPath . $result['file'];
        } catch (\Exception $e) {
            $this->logger->error('Avatar upload failed: ' . $e->getMessage(), ['exception' => $e]);
            $this->messageManager->addError($e->getMessage());
        }

    }
}
