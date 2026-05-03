# Changelog

## 1.0.0 — Initial release

### Architecture
- Declarative schema (`db_schema.xml`) — no `InstallSchema` scripts
- Repository pattern with `Api/` interfaces (Forum, Topic, Post, Moderator, Report)
- ViewModel pattern for frontend presentation (no Block proxy layer)
- Shared `AbstractRepository` base class with `getList()` logic
- Service classes: `AuthorisationService`, `NotificationService`, `CounterUpdater`, `UrlKeyGenerator`, `VisitorTracker`
- Traits: `PostDataProcessorTrait`, `AutoTimestampTrait`

### Features
- Hierarchical forums with SEO-friendly URL keys
- Topic discussions with posts, pagination, and view tracking
- Post reporting system with admin review queue
- Moderation queue — non-moderator posts set to pending, admin approve/reject
- Customer profiles: nickname, avatar, signature
- Email subscriptions with cryptographically secure unsubscribe
- Visitor tracking — "Who's Online" per forum/topic
- Bookmarks for favorite topics
- RSS feeds per forum
- Search (database-level LIKE query)
- Customer group-based forum access control

### APIs
- 14 REST endpoints (`webapi.xml`)
- 6 GraphQL queries (`schema.graphqls`) with resolver processors

### Admin
- UI component grids with mass actions (delete, enable, disable, approve, reject)
- Admin dashboard widget (forum stats)
- Pending count badge on admin menu
- Customer group multiselect on forum edit form

### Security
- CSRF via `FormKeyValidator` on all POST actions
- XSS prevention via `escapeHtml/escapeUrl` in all templates
- `cleanJs()` sanitization on post content (script tags, event handlers)
- CSPRNG unsubscribe hashes (`random_bytes(16)`)

### Database
- 9 tables: `forum`, `forum_topic`, `forum_post`, `forum_report`, `forum_moderator`, `forum_access`, `forum_visitor`, `forum_notification`, `forum_usersettings`
- Foreign keys with CASCADE deletes
- Indices on all filter columns including `is_deleted`
- Unique constraint on notification `(user_id, topic_id)`
