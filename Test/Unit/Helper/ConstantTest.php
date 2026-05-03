<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Test\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Ronald2Wing\Forum\Api\Data\ForumInterface;
use Ronald2Wing\Forum\Api\Data\TopicInterface;
use Ronald2Wing\Forum\Api\Data\PostInterface;
use Ronald2Wing\Forum\Helper\Constant;

class ConstantTest extends TestCase
{
    public function testParamConstants(): void
    {
        $this->assertSame('limit', Constant::PARAM_LIMIT);
        $this->assertSame('p', Constant::PARAM_PAGE);
        $this->assertSame('sort', Constant::PARAM_SORT);
        $this->assertSame('uid', Constant::PARAM_USER_ID);
        $this->assertSame('topic_id', Constant::PARAM_TOPIC_ID);
        $this->assertSame('post_id', Constant::PARAM_POST_ID);
        $this->assertSame('id', Constant::PARAM_FORUM_ID);
    }

    public function testSearchConstants(): void
    {
        $this->assertSame('forum_search', Constant::SEARCH_QUERY);
        $this->assertSame('forum_search_phrase', Constant::SEARCH_SESSION_KEY);
        $this->assertSame('search_type', Constant::SEARCH_TYPE);
    }

    public function testSortConstants(): void
    {
        $this->assertSame('created_at_asc', Constant::SORT_CREATED_ASC);
        $this->assertSame('created_at_desc', Constant::SORT_CREATED_DESC);
        $this->assertSame('title_asc', Constant::SORT_TITLE_ASC);
        $this->assertSame('title_desc', Constant::SORT_TITLE_DESC);
        $this->assertSame('posts_count_asc', Constant::SORT_POSTS_ASC);
        $this->assertSame('posts_count_desc', Constant::SORT_POSTS_DESC);
        $this->assertSame('views_count_asc', Constant::SORT_VIEWS_ASC);
        $this->assertSame('views_count_desc', Constant::SORT_VIEWS_DESC);
    }

    public function testPagerConstants(): void
    {
        $this->assertSame('forum', Constant::PAGER_FORUM);
        $this->assertSame('topic', Constant::PAGER_TOPIC);
        $this->assertSame('post', Constant::PAGER_POST);
        $this->assertSame('customer_posts', Constant::PAGER_CUSTOMER_POSTS);
        $this->assertSame('customer_topics', Constant::PAGER_CUSTOMER_TOPICS);
        $this->assertSame('search', Constant::PAGER_SEARCH);
        $this->assertSame('search_post', Constant::PAGER_SEARCH_POST);
        $this->assertSame('search_topic', Constant::PAGER_SEARCH_TOPIC);
    }

    public function testDefaultPageSizes(): void
    {
        $this->assertSame(10, Constant::DEFAULT_PAGE_SIZE_FORUM);
        $this->assertSame(20, Constant::DEFAULT_PAGE_SIZE_TOPIC);
        $this->assertSame(20, Constant::DEFAULT_PAGE_SIZE_POST);
    }

    public function testRouteNameConstant(): void
    {
        $this->assertSame('ronald2wing_forum', Constant::FRONTEND_ROUTE_NAME);
    }

    public function testStatusConstants(): void
    {
        $this->assertSame(1, ForumInterface::STATUS_ENABLED);
        $this->assertSame(0, ForumInterface::STATUS_DISABLED);
        $this->assertSame(1, TopicInterface::STATUS_ENABLED);
        $this->assertSame(0, TopicInterface::STATUS_DISABLED);
        $this->assertSame(1, PostInterface::STATUS_ENABLED);
        $this->assertSame(0, PostInterface::STATUS_DISABLED);
    }

    public function testBookmarkConstants(): void
    {
        $this->assertSame('forum_bookmarks', Constant::BOOKMARK_KEY);
        $this->assertSame('topic_ids', Constant::BOOKMARK_TOPIC_IDS);
    }

    public function testRegistryConstants(): void
    {
        $this->assertSame('forum_customer_session', Constant::REGISTRY_CUSTOMER_SESSION);
        $this->assertSame('forum_parent_forum', Constant::REGISTRY_PARENT_FORUM);
        $this->assertSame('forum_parent_topic', Constant::REGISTRY_PARENT_TOPIC);
        $this->assertSame('forum_search_phrase', Constant::REGISTRY_SEARCH_QUERY);
        $this->assertSame('search_type', Constant::REGISTRY_SEARCH_TYPE);
        $this->assertSame('forum_bookmarks', Constant::REGISTRY_BOOKMARKS);
        $this->assertSame('forum_user_id', Constant::REGISTRY_USER_ID);
    }

    public function testAvatarConstants(): void
    {
        $this->assertSame('forum/avatar/', Constant::AVATAR_PATH);
        $this->assertSame('ronald2wingforum/avatar/', Constant::AVATAR_DIR);
        $this->assertSame('no-image.png', Constant::AVATAR_NO_IMAGE);
    }

    public function testAdminUserIdConstant(): void
    {
        $this->assertSame(0, Constant::ADMIN_USER_ID);
    }
}
