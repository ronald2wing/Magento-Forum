<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Model\ResourceModel\Forum;

if (!class_exists(CollectionFactory::class)) {
    class CollectionFactory
    {
        public function create(array $data = []): Collection
        {
            return new Collection();
        }
    }
}

namespace Ronald2Wing\Forum\Model\ResourceModel\Topic;

if (!class_exists(CollectionFactory::class)) {
    class CollectionFactory
    {
        public function create(array $data = []): Collection
        {
            return new Collection();
        }
    }
}

namespace Ronald2Wing\Forum\Model\ResourceModel\Post;

if (!class_exists(CollectionFactory::class)) {
    class CollectionFactory
    {
        public function create(array $data = []): Collection
        {
            return new Collection();
        }
    }
}

namespace Ronald2Wing\Forum\Model\ResourceModel\Moderator;

if (!class_exists(CollectionFactory::class)) {
    class CollectionFactory
    {
        public function create(array $data = []): Collection
        {
            return new Collection();
        }
    }
}

namespace Ronald2Wing\Forum\Model\ResourceModel\Notification;

if (!class_exists(CollectionFactory::class)) {
    class CollectionFactory
    {
        public function create(array $data = []): Collection
        {
            return new Collection();
        }
    }
}

namespace Ronald2Wing\Forum\Model\ResourceModel\Visitor;

if (!class_exists(CollectionFactory::class)) {
    class CollectionFactory
    {
        public function create(array $data = []): Collection
        {
            return new Collection();
        }
    }
}

namespace Ronald2Wing\Forum\Model\ResourceModel\UserSettings;

if (!class_exists(CollectionFactory::class)) {
    class CollectionFactory
    {
        public function create(array $data = []): Collection
        {
            return new Collection();
        }
    }
}

namespace Ronald2Wing\Forum\Model;

if (!class_exists(ForumFactory::class)) {
    class ForumFactory
    {
        public function create(array $data = []): \Ronald2Wing\Forum\Model\Forum
        {
            return new \Ronald2Wing\Forum\Model\Forum();
        }
    }
}

if (!class_exists(TopicFactory::class)) {
    class TopicFactory
    {
        public function create(array $data = []): \Ronald2Wing\Forum\Model\Topic
        {
            return new \Ronald2Wing\Forum\Model\Topic();
        }
    }
}

if (!class_exists(PostFactory::class)) {
    class PostFactory
    {
        public function create(array $data = []): \Ronald2Wing\Forum\Model\Post
        {
            return new \Ronald2Wing\Forum\Model\Post();
        }
    }
}

if (!class_exists(ModeratorFactory::class)) {
    class ModeratorFactory
    {
        public function create(array $data = []): \Ronald2Wing\Forum\Model\Moderator
        {
            return new \Ronald2Wing\Forum\Model\Moderator();
        }
    }
}

if (!class_exists(NotificationFactory::class)) {
    class NotificationFactory
    {
        public function create(array $data = []): \Ronald2Wing\Forum\Model\Notification
        {
            return new \Ronald2Wing\Forum\Model\Notification();
        }
    }
}

if (!class_exists(VisitorFactory::class)) {
    class VisitorFactory
    {
        public function create(array $data = []): \Ronald2Wing\Forum\Model\Visitor
        {
            return new \Ronald2Wing\Forum\Model\Visitor();
        }
    }
}

if (!class_exists(UserSettingsFactory::class)) {
    class UserSettingsFactory
    {
        public function create(array $data = []): \Ronald2Wing\Forum\Model\UserSettings
        {
            return new \Ronald2Wing\Forum\Model\UserSettings();
        }
    }
}
