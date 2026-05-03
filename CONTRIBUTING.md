# Contributing

## Development setup

```bash
# Install in Magento app/code directory
cd app/code && git clone <repo-url> Ronald2Wing/Forum

# Enable and upgrade
bin/magento module:enable Ronald2Wing_Forum
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:clean
```

## Code standards

- `declare(strict_types=1)` on every PHP file
- Full type hints on all parameters and return types
- Constructor DI with `private readonly` — never `ObjectManager`
- Repository pattern: no direct model/collection instantiation
- ViewModels for presentation logic — no Block logic beyond admin form tabs

## Testing

```bash
vendor/bin/phpunit -c app/code/Ronald2Wing/Forum/phpunit.xml
vendor/bin/phpunit --filter ForumTest
vendor/bin/phpunit --filter "testGetByIdReturns"
```

Pure unit tests with PHPUnit mocks. No integration framework required.

## Before submitting

- No `FIXME`/`TODO`/`HACK` comments
- No `getData()` calls bypassing typed getters
- No `parent_id`, `system_user_id`, `created_time`, or `url_text` column names
- Verify with `php -l` on all changed files
