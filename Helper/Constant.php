<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Helper;

class Constant
{
    public const ADMIN_USER_ID = 0;

    public const PARAM_LIMIT = 'limit';
    public const PARAM_PAGE = 'p';
    public const PARAM_SORT = 'sort';
    public const PARAM_USER_ID = 'uid';
    public const PARAM_TOPIC_ID = 'topic_id';
    public const PARAM_POST_ID = 'post_id';
    public const PARAM_FORUM_ID = 'id';

    public const SEARCH_QUERY = 'forum_search';
    public const SEARCH_SESSION_KEY = 'forum_search_phrase';
    public const SEARCH_TYPE = 'search_type';

    public const BOOKMARK_KEY = 'forum_bookmarks';
    public const BOOKMARK_TOPIC_IDS = 'topic_ids';

    public const RSS_FORUM_ID = 'rss_forum_id';
    public const RSS_TOPIC_ID = 'rss_topic_id';

    public const SORT_CREATED_ASC = 'created_at_asc';
    public const SORT_CREATED_DESC = 'created_at_desc';
    public const SORT_TITLE_ASC = 'title_asc';
    public const SORT_TITLE_DESC = 'title_desc';
    public const SORT_POSTS_ASC = 'posts_count_asc';
    public const SORT_POSTS_DESC = 'posts_count_desc';
    public const SORT_VIEWS_ASC = 'views_count_asc';
    public const SORT_VIEWS_DESC = 'views_count_desc';

    public const PAGER_FORUM = 'forum';
    public const PAGER_TOPIC = 'topic';
    public const PAGER_POST = 'post';
    public const PAGER_CUSTOMER_POSTS = 'customer_posts';
    public const PAGER_CUSTOMER_TOPICS = 'customer_topics';
    public const PAGER_SEARCH = 'search';

    public const DEFAULT_PAGE_SIZE_FORUM = 10;
    public const DEFAULT_PAGE_SIZE_TOPIC = 20;
    public const DEFAULT_PAGE_SIZE_POST = 20;

    public const EDIT_OBJECT_TOPIC = 'ronald2wing_forum_edit_topic';
    public const EDIT_OBJECT_POST = 'ronald2wing_forum_edit_post';
    public const EDIT_OBJECT_FORUM = 'ronald2wing_forum_edit_forum';

    public const FRONTEND_ROUTE_NAME = 'ronald2wing_forum';

    public const AVATAR_PATH = 'forum/avatar/';
    public const AVATAR_DIR = 'ronald2wingforum/avatar/';
    public const AVATAR_NO_IMAGE = 'no-image.png';

    public const POST_BLOCK_ID_PREFIX = 'ronald2wing_forum_post_';

    public const SORT_FIELD_CREATED_AT = 'created_at';

    public const SEARCH_TYPE_POST = 'post';
    public const SEARCH_TYPE_TOPIC = 'topic';

    public const REGISTRY_CUSTOMER_SESSION = 'forum_customer_session';
    public const REGISTRY_PARENT_FORUM = 'forum_parent_forum';
    public const REGISTRY_PARENT_TOPIC = 'forum_parent_topic';
    public const REGISTRY_SEARCH_QUERY = 'forum_search_phrase';
    public const REGISTRY_SEARCH_TYPE = 'search_type';
    public const REGISTRY_BOOKMARKS = 'forum_bookmarks';
    public const REGISTRY_RSS_TOPIC = 'forum_rss_topic';
    public const REGISTRY_RSS_FORUMS = 'forum_rss_forums';
    public const REGISTRY_USER_ID = 'forum_user_id';

    public const PAGER_SEARCH_POST = 'search_post';
    public const PAGER_SEARCH_TOPIC = 'search_topic';
}
