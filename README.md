# Magento 2 Forum

Full-featured forum system for Magento 2 — forums, topics, posts, moderation, reporting, GraphQL, and REST API.

## Features

- **Forum hierarchy**: Forums → Topics → Posts
- **SEO-friendly URLs**: custom router with clean keys
- **Moderation queue**: non-moderator posts set to pending, admin approve/reject
- **Post reporting**: users flag posts, admin reviews
- **Customer profiles**: nickname, avatar, signature
- **Email notifications**: topic subscriptions with secure unsubscribe
- **Visitor tracking**: "Who's online" per forum and topic
- **Bookmarks**, **RSS feeds**, **search**, **customer group access**
- **REST API**: 14 endpoints
- **GraphQL**: 6 queries with resolver-processed relation fields

## Requirements

- PHP 8.1+
- Magento 2.4.6+
- MySQL / MariaDB

## Install

```bash
composer require ronald2wing/module-forum
bin/magento module:enable Ronald2Wing_Forum
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:clean
```

## Configuration

**Stores → Configuration → Ronald2Wing Extensions → Magento Forum**

| Setting            | Default          |
| ------------------ | ---------------- |
| Enable Forum       | Yes              |
| Forum Route        | `forum`          |
| Enable Search      | Yes              |
| Enable Bookmarks   | Yes              |
| Enable RSS         | Yes              |
| Enable Statistics  | Yes              |
| Who's Online       | Yes              |
| Page Layout        | 1 column         |
| Notification Email | owner@example.com |

## REST API

| Method | Endpoint                        | Access    |
| ------ | ------------------------------- | --------- |
| GET    | `/V1/forums`                      | Anonymous |
| GET    | `/V1/forums/:forumId`             | Anonymous |
| POST   | `/V1/forums`                      | Admin     |
| DELETE | `/V1/forums/:forumId`             | Admin     |
| GET    | `/V1/forums/:forumId/topics`      | Anonymous |
| GET    | `/V1/topics/:topicId`             | Anonymous |
| POST   | `/V1/topics`                      | Customer  |
| DELETE | `/V1/topics/:topicId`             | Customer  |
| GET    | `/V1/topics/:topicId/posts`       | Anonymous |
| GET    | `/V1/posts/:postId`               | Anonymous |
| POST   | `/V1/posts`                       | Customer  |
| DELETE | `/V1/posts/:postId`               | Customer  |
| GET    | `/V1/forum/moderators`           | Admin     |
| POST   | `/V1/forum/moderators`           | Admin     |

## GraphQL

```graphql
{
  forums(pageSize: 10, currentPage: 1) {
    items { forum_id title url_key last_post { content created_at } }
    total_count
  }
  forumTopics(forumId: 1) {
    items { topic_id title total_posts first_post { content } last_post { content } }
  }
  forumPosts(topicId: 42) {
    items { post_id content user_id created_at }
  }
  forumSearch(input: { query: "search", type: "post" }) {
    items { post_id content }
  }
}
```

## Database

| Table                  | Purpose                       |
| ---------------------- | ----------------------------- |
| `forum`                | Forum categories              |
| `forum_topic`          | Discussion topics             |
| `forum_post`           | Posts and replies             |
| `forum_report`         | Post reports / flags          |
| `forum_moderator`      | Moderator assignments         |
| `forum_access`         | Customer group permissions    |
| `forum_visitor`        | Online visitor tracking       |
| `forum_notification`   | Email subscriptions           |
| `forum_usersettings`   | User profiles                 |

## Architecture

- Repository pattern with API interfaces
- Declarative schema (`db_schema.xml`)
- ViewModel pattern — no Block proxy layer
- Service classes: `AuthorisationService`, `NotificationService`, `CounterUpdater`, `UrlKeyGenerator`, `VisitorTracker`
- GraphQL resolvers with `@resolver` processors for relation fields
- CSRF on all POST, XSS prevention in all templates

## Test

```bash
vendor/bin/phpunit -c app/code/Ronald2Wing/Forum/phpunit.xml
```

## License

MIT
