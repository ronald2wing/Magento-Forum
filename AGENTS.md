# AGENTS.md

Magento 2 forum module. Namespace `Ronald2Wing\Forum`.

## Architecture

- No Block proxy layer. ViewModels are wired directly in layout XML via `<argument name="view_model" xsi:type="object">`. Templates access them as `$block->getViewModel()`.
- Helper `Helper\Data` is wired as `<argument name="forum_data">` and accessed via `$block->getForumData()`.
- All data access goes through repositories (`Api/*RepositoryInterface`). Never instantiate models or collections directly outside the repository.
- Frontend router: `Controller\Router` matches `/forum/{forum-slug}[/{topic-slug}]` and forwards to `Forum\Index`, `Forum\View`, or `Topic\Index`.
- Admin grids use declarative UI components in `view/adminhtml/ui_component/`. Grid data sources are virtual types in `etc/di.xml`.
- Traits for shared behavior: `PostDataProcessorTrait` (JS sanitization), `AutoTimestampTrait` (created_at/updated_at), `AbstractActions` (admin grid action columns).
- `Model\AbstractRepository` holds shared `getList()` logic. All 4 repositories extend it.

## Table naming

| Table              | PK              | FKs                          | Notes                        |
| ------------------ | --------------- | ---------------------------- | ---------------------------- |
| `forum`              | `forum_id`        | `parent_id` (self)             | Was `forum_category`           |
| `forum_topic`        | `topic_id`        | `forum_id`                     |                              |
| `forum_post`         | `post_id`         | `topic_id`, `forum_id`           | `forum_id` is denormalized       |
| `forum_report`       | `report_id`       | `post_id`                      | Post reporting system        |
| `forum_moderator`    | `moderator_id`    |                               |                              |
| `forum_access`       | `access_id`       | `forum_id`                     | Customer group permissions   |
| `forum_visitor`      | `visitor_id`      |                               | Online tracking               |
| `forum_notification` | `notification_id` | `topic_id`                     | Email subscriptions           |
| `forum_usersettings` | `settings_id`     |                               | User profiles                 |

- All timestamp columns: `created_at` / `updated_at` (never `created_time` / `update_time`).
- All user FKs: `user_id` (never `system_user_id`).
- URL slug columns: `url_key` (never `url_text`).
- Post body: `content` / `content_original` (never `post` / `post_orig`).
- Notification hash: `unsubscribe_hash` (never `hash` or `notify_hash`).

## Key files

| File                                           | Purpose                                   |
| ---------------------------------------------- | ----------------------------------------- |
| `etc/db_schema.xml`                              | Declarative schema (no InstallSchema)     |
| `etc/webapi.xml`                                 | 14 REST endpoints                         |
| `etc/schema.graphqls`                            | GraphQL schema + 6 queries                |
| `etc/events.xml`                                 | Topic/Post save_after observers           |
| `etc/adminhtml/di.xml`                           | Admin DI (pending count plugin)           |
| `Api/Data/*Interface.php`                        | Entity interfaces                         |
| `Model/Service/AuthorisationService.php`         | `isModerator()`, `isAllowed()`, `isOwner()` |
| `Model/Service/NotificationService.php`          | Email subscriptions + send                |
| `Model/Service/CounterUpdater.php`               | Topic/forum post counts                   |
| `Model/Service/UrlKeyGenerator.php`              | SEO slug generation + uniqueness          |
| `Model/Service/VisitorTracker.php`               | Who's online tracking                     |
| `Model/AbstractRepository.php`                   | Shared `getList()` logic                    |
| `Model/Resolver/PostTransformer.php`             | GraphQL post field mapping                |
| `Model/Resolver/ForumSearch.php`                 | DB-level LIKE search (not in-memory)      |
| `Ui/Component/Listing/Column/AbstractActions.php`| Shared admin grid action column           |
| `Plugin/Menu/AddPendingCount.php`                | Admin menu pending count badge            |
| `Block/Adminhtml/Dashboard/Stats.php`            | Admin dashboard widget                    |

## Status constants

- `PostInterface::STATUS_ENABLED = 1`, `STATUS_DISABLED = 0`, `STATUS_PENDING = 2`
- `TopicInterface::STATUS_ENABLED = 1`, `STATUS_DISABLED = 0`, `STATUS_PENDING = 2`
- `ReportInterface::STATUS_NEW = 0`, `STATUS_REVIEWED = 1`
- **Never mix** `PostInterface::STATUS_ENABLED` to filter a topic collection or vice versa.

## Security rules

- Every POST controller checks `FormKeyValidator`. Pattern: `if (!$this->formKeyValidator->validate($this->request)) { ... }`
- Templates escape all user data: `$block->escapeHtml()`, `$block->escapeHtmlAttr()`, `$block->escapeUrl()`. Post content (rich HTML) is the only exception — sanitized by the data processor.
- `PostDataProcessorTrait::cleanJs()` strips `<script>`, `on*` handlers (single + double quotes), and `javascript:` from post content. Apply for all user-submitted HTML.
- Unsubscribe hashes use `random_bytes(16)` — never `uniqid()`.
- Customer signature field must also run through `cleanJs()` (not just `strip_tags`).

## Tests

```bash
vendor/bin/phpunit -c phpunit.xml              # root of module
vendor/bin/phpunit --filter ForumTest          # single test class
vendor/bin/phpunit --filter testGetByIdReturns  # single test method
```

Pure unit tests with PHPUnit mocks. No Magento integration test framework. Stubs in `Test/Unit/Stub/`.

## Common pitfalls

- `Model\Forum` maps to `forum` table, PK `forum_id`. Never use `topic_id` or `category_id` for forums.
- `Model\UserSettings` PK is `settings_id`, not `user_id`. `getId()` → `settings_id`, `getUserId()` → `user_id`.
- `AuthorisationService` caches `isModerator()` per-instance. Don't reuse across requests.
- `VisitorTracker::registerVisitation()` column is `visited_at`, not `time_visited`.
- `CounterUpdater::updateTopicCounts()` also cascades to `updateForumCounts()`.
- `NotificationService::subscribe()` signature is `subscribe(int $userId, int $topicId)` — userId FIRST.
- GraphQL `last_post`/`first_post` are resolved by `@resolver` processors in schema.graphqls — not populated in the main query resolver.
- Admin `MassApprove` triggers `sendNotification()` to subscribers (respects config flag).
- Delete controllers must call `CounterUpdater` after soft-delete to keep parent counters accurate.
- `forum_access` data key on Forum model is `customer_groups` — must match form field name.
