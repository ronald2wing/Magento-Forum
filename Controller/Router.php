<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Controller;

use Ronald2Wing\Forum\Helper\Constant;
use Ronald2Wing\Forum\Helper\Data as ForumData;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Ronald2Wing\Forum\Model\ForumFactory;
use Ronald2Wing\Forum\Model\TopicFactory;

class Router implements RouterInterface
{
    public function __construct(
        private readonly ActionFactory $actionFactory,
        private readonly EventManager $eventManager,
        private readonly UrlInterface $url,
        private readonly StoreManagerInterface $storeManager,
        private readonly ResponseInterface $response,
        private readonly ForumFactory $forumFactory,
        private readonly TopicFactory $topicFactory,
        private readonly ForumData $forumData
    ) {}

    public function match(RequestInterface $request): ?\Magento\Framework\App\ActionInterface
    {
        if (!$this->forumData->isEnabled()) {
            return null;
        }

        $identifier = trim($request->getPathInfo(), '/');
        if ($identifier === '') {
            return null;
        }

        $forumRoute = $this->forumData->getRoute();
        $paths = explode('/', $identifier, 2);
        $currentRoute = trim($paths[0]);

        if ($currentRoute !== $forumRoute) {
            return null;
        }

        $forumUrlPath = '';
        $topicUrlPath = '';

        if (!empty($paths[1])) {
            $forumUrlPath = $paths[1];
        }

        if ($forumUrlPath === '') {
            $this->setDefaultAction($request);
        } else {
            if (strstr($forumUrlPath, '/')) {
                $pathsTwo = explode('/', $forumUrlPath, 2);
                $forumUrlPath = trim($pathsTwo[0]);
                $topicUrlPath = trim($pathsTwo[1] ?? '');
            }

            $forum = $this->getItemByRoute($forumUrlPath);
            if (!$forum) {
                return null;
            }

            if ($topicUrlPath === '') {
                $this->setForumViewAction($request, (int) $forum->getId());
            } else {
                $topic = $this->getItemTopicByRoute($topicUrlPath);
                if (!$topic) {
                    return null;
                }
                $this->setTopicViewAction($request, (int) $topic->getId());
            }
        }

        $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $identifier);

        return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
    }

    private function setTopicViewAction(RequestInterface $request, int $topicId): void
    {
        $request->setModuleName(Constant::FRONTEND_ROUTE_NAME)
            ->setControllerName('topic')
            ->setActionName('index')
            ->setParam(Constant::PARAM_TOPIC_ID, $topicId);
    }

    private function setForumViewAction(RequestInterface $request, int $forumId): void
    {
        $request->setModuleName(Constant::FRONTEND_ROUTE_NAME)
            ->setControllerName('forum')
            ->setActionName('view')
            ->setParam(Constant::PARAM_FORUM_ID, $forumId);
    }

    private function setDefaultAction(RequestInterface $request): void
    {
        $request->setModuleName(Constant::FRONTEND_ROUTE_NAME)
            ->setControllerName('forum')
            ->setActionName('index');
    }

    private function getItemByRoute(string $route): ?\Ronald2Wing\Forum\Model\Forum
    {
        $model = $this->forumFactory->create();
        $model->load($route, 'url_key');
        if ($model->getId() && !$model->getIsDeleted()) {
            return $model;
        }
        return null;
    }

    private function getItemTopicByRoute(string $route): ?\Ronald2Wing\Forum\Model\Topic
    {
        $model = $this->topicFactory->create();
        $model->load($route, 'url_key');
        if ($model->getId() && !$model->getIsDeleted()) {
            return $model;
        }
        return null;
    }
}
