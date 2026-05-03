<?php
declare(strict_types=1);

namespace Magento\Framework\App\Helper;

if (!class_exists(AbstractHelper::class)) {
    abstract class AbstractHelper
    {
        protected \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig;
        public function __construct(Context $context)
        {
            $this->scopeConfig = $context->getScopeConfig();
        }
    }
    class Context
    {
        public function __construct(
            private readonly \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
        ) {}
        public function getScopeConfig(): \Magento\Framework\App\Config\ScopeConfigInterface
        {
            return $this->scopeConfig;
        }
    }
}

namespace Magento\Framework\App\Config;

if (!interface_exists(ScopeConfigInterface::class)) {
    interface ScopeConfigInterface
    {
        public function getValue(?string $path, string $scopeType = 'default', ?int $scopeCode = null);
        public function isSetFlag(string $path, string $scopeType = 'default', ?int $scopeCode = null): bool;
    }
}

namespace Magento\Framework;

if (!class_exists(DataObject::class)) {
    class DataObject
    {
        private array $data = [];

        public function setData(string $key, mixed $value): static
        {
            $this->data[$key] = $value;
            return $this;
        }

        public function getData(?string $key = null): mixed
        {
            if ($key === null) {
                return $this->data;
            }
            return $this->data[$key] ?? null;
        }

        public function hasData(string $key): bool
        {
            return array_key_exists($key, $this->data);
        }

        public function unsetData(?string $key = null): static
        {
            if ($key === null) {
                $this->data = [];
            } else {
                unset($this->data[$key]);
            }
            return $this;
        }
    }
}

if (!interface_exists(UrlInterface::class)) {
    interface UrlInterface
    {
        public const URL_TYPE_LINK = 'link';
        public function getUrl(string $routePath = '', ?array $routeParams = null): string;
        public function getBaseUrl(string $type = ''): string;
    }
}

namespace Magento\Framework\Model;

if (!class_exists(AbstractModel::class)) {
    abstract class AbstractModel extends \Magento\Framework\DataObject
    {
        protected $_cacheTag;
        protected $_eventPrefix;
        protected $_eventObject;

        abstract protected function _construct(): void;

        protected function _init(string $resourceModel): void {}

        public function getId(): ?int
        {
            return null;
        }

        public function getIdentities(): array
        {
            return $this->getId() !== null
                ? [$this->_cacheTag . '_' . $this->getId()]
                : [];
        }
    }
}

namespace Magento\Framework\DataObject;

if (!interface_exists(IdentityInterface::class)) {
    interface IdentityInterface
    {
        public function getIdentities(): array;
    }
}

namespace Magento\Framework\Data;

if (!interface_exists(OptionSourceInterface::class)) {
    interface OptionSourceInterface
    {
        public function toOptionArray(): array;
    }
}

if (!class_exists(Collection::class)) {
    class Collection implements \IteratorAggregate, \Countable
    {
        public const SORT_ORDER_ASC = 'ASC';
        public const SORT_ORDER_DESC = 'DESC';

        public function addFieldToFilter($field, $condition = null): static
        {
            return $this;
        }

        public function getSize(): int
        {
            return 0;
        }

        public function getIterator(): \Traversable
        {
            return new \ArrayIterator([]);
        }

        public function count(): int
        {
            return 0;
        }
    }
}

if (!interface_exists(EntityFactoryInterface::class)) {
    interface EntityFactoryInterface {}
}

namespace Magento\Framework\Data\Collection\Db;

if (!interface_exists(FetchStrategyInterface::class)) {
    interface FetchStrategyInterface {}
}

namespace Magento\Framework\Model\ResourceModel\Db;

if (!class_exists(Context::class)) {
    class Context {}
}

if (!class_exists(AbstractDb::class)) {
    class AbstractDb
    {
        public function __construct(?Context $context = null, ?string $connectionName = null) {}

        public function load(\Magento\Framework\Model\AbstractModel $object, mixed $value, ?string $field = null): self
        {
            return $this;
        }

        public function save(\Magento\Framework\Model\AbstractModel $object): self
        {
            return $this;
        }

        public function delete(\Magento\Framework\Model\AbstractModel $object): self
        {
            return $this;
        }

        public function getConnection(): \Magento\Framework\DB\Adapter\AdapterInterface
        {
            return new class implements \Magento\Framework\DB\Adapter\AdapterInterface {
                public function beginTransaction(): void {}
                public function commit(): void {}
                public function rollBack(): void {}
                public function getConnection(): \Magento\Framework\DB\Adapter\AdapterInterface { return $this; }
                public function isConnectionAvailable(): bool { return true; }
                public function query($sql, $bind = []): mixed { throw new \RuntimeException('Not implemented'); }
                public function fetchAll($sql, $bind = [], $fetchMode = null): array { return []; }
                public function fetchRow($sql, $bind = [], $fetchMode = null) { return []; }
                public function fetchAssoc($sql, $bind = []) { return []; }
                public function fetchCol($sql, $bind = []) { return []; }
                public function fetchOne($sql, $bind = []) { return ''; }
                public function fetchPairs($sql, $bind = []) { return []; }
                public function insert($table, array $data) { return 0; }
                public function insertMultiple($table, array $data) { return 0; }
                public function insertOnDuplicate($table, array $data, array $fields = []) { return 0; }
                public function insertFromSelect(\Magento\Framework\DB\Select $select, $table, array $fields = [], $mode = false) { return 0; }
                public function delete($table, $where = '') { return 0; }
                public function update($table, array $bind, $where = '') { return 0; }
                public function lastInsertId($tableName = null, $primaryKey = null) { return '0'; }
                public function quote($value, $type = null) { return "'$value'"; }
                public function quoteInto($text, $value, $type = null, $count = null) { return ''; }
                public function quoteIdentifier($ident, $auto = false) { return $ident; }
                public function quoteColumnAs($ident, $alias, $auto = false) { return "$ident AS $alias"; }
                public function quoteTableAs($ident, $alias = null, $auto = false) { return $ident; }
                public function describeTable($tableName, $schemaName = null) { return []; }
                public function createTable(\Magento\Framework\DB\Ddl\Table $table) { return $this; }
                public function dropTable($tableName, $schemaName = null) { return $this; }
                public function truncateTable($tableName, $schemaName = null) { return $this; }
                public function addColumn($tableName, $columnName, $definition) { return $this; }
                public function dropColumn($tableName, $columnName, $schemaName = null) { return $this; }
                public function changeColumn($tableName, $oldColumnName, $newColumnName, $definition) { return $this; }
                public function modifyColumn($tableName, $columnName, $definition) { return $this; }
                public function showTableStatus($tableName, $schemaName = null) { return []; }
                public function getCreateTable($tableName, $schemaName = null) { return ''; }
                public function getForeignKeyByTable($tableName, $schemaName = null) { return []; }
                public function getForeignKeys($tableName, $schemaName = null) { return []; }
                public function createTableByDdl($tableName, $newTableName) { return $this; }
                public function resetDdlCache($tableName = null, $schemaName = null) { return $this; }
                public function select() { return new \Magento\Framework\DB\Select($this); }
                public function fetchAllByHash($value, $tableName, ...$args) { return []; }
                public function getTableName($tableName) { return $tableName; }
                public function getTriggerName($tableName, $time, $event) { return ''; }
                public function changeTableEngine($tableName, $engine) { return $this; }
                public function changeTableComment($tableName, $comment) { return $this; }
                public function getFullTextIndex($tableName, $schemaName = null) { return []; }
                public function getTableEngine($tableName, $schemaName = null) { return 'InnoDB'; }
                public function getTableComment($tableName, $schemaName = null) { return ''; }
                public function getIfnullSql($expression, $value = 0) { return ''; }
                public function getCaseSql($valueName, $casesResults, $defaultValue = null) { return ''; }
                public function getConcatSql(array $data, $separator = null) { return ''; }
                public function getLengthSql($string) { return ''; }
                public function getLeastSql(array $data) { return ''; }
                public function getGreatestSql(array $data) { return ''; }
                public function getDateAddSql($date, $interval, $unit) { return ''; }
                public function getDateSubSql($date, $interval, $unit) { return ''; }
                public function getDateFormatSql($date, $format) { return ''; }
                public function getDatePartSql($date) { return ''; }
                public function getSubstringSql($stringExpression, $pos, $len = null) { return ''; }
                public function getStandardDeviationSql($expressionField) { return ''; }
                public function getDateExtractSql($date, $unit) { return ''; }
                public function getTableChecksum($tableName, $schemaName = null): int { return 0; }
                public function supportStraightJoin(): bool { return false; }
                public function orderRand(\Magento\Framework\DB\Select $select, $field = null): void {}
                public function forUpdate($sql): string { return $sql; }
                public function getPrimaryKeyName(\Magento\Framework\DB\Ddl\Table $table): string { return ''; }
                public function decodeDateField($expression): string { return $expression; }
                public function getTransactionLevel(): int { return 0; }
                public function getIndexList($tableName, $schemaName = null): array { return []; }
            };
        }

        protected function _construct(): void {}

        public function getTable(string $tableName): string { return $tableName; }
    }
}

namespace Magento\Framework\Model\ResourceModel\Db\Collection;

if (!class_exists(AbstractCollection::class)) {
    class AbstractCollection extends \Magento\Framework\Data\Collection
    {
        protected $_idFieldName;

        public function __construct(
            ?\Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory = null,
            ?\Psr\Log\LoggerInterface $logger = null,
            ?\Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy = null,
            ?\Magento\Framework\Event\ManagerInterface $eventManager = null,
            ?\Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
            ?AbstractDb $resource = null
        ) {
            parent::__construct();
        }

        public function load($printQuery = false, $logQuery = false): self { return $this; }

        public function addFieldToFilter($field, $condition = null): static { return $this; }

        public function setOrder($field, $direction = self::SORT_ORDER_DESC): static { return $this; }

        public function setCurPage($page): static { return $this; }

        public function setPageSize($size): static { return $this; }

        public function getSize(): int { return 0; }

        public function getItems(): array { return []; }

        public function getFirstItem(): object { return new \stdClass(); }

        public function getLastItem(): object { return new \stdClass(); }

        protected function _init(string $model, string $resourceModel): void {}

        protected function _beforeLoad(): self { return $this; }

        protected function _toOptionArray(string $valueField, string $labelField): array { return []; }

        public function getById(int $id): ?object { return null; }

        public function enabledOnly(): self { return $this; }
    }
}

namespace Magento\Store\Model;

if (!interface_exists(ScopeInterface::class)) {
    interface ScopeInterface
    {
        public const SCOPE_STORE = 'store';
        public const SCOPE_WEBSITE = 'website';
        public const SCOPE_GROUP = 'group';
        public const SCOPE_DEFAULT = 'default';
    }
}

if (!interface_exists(StoreManagerInterface::class)) {
    interface StoreManagerInterface
    {
        public function getStore(?int $storeId = null): Store;
    }
}

if (!class_exists(Store::class)) {
    class Store
    {
        public function getBaseUrl(string $type = '', ?bool $secure = null): string
        {
            return '';
        }
    }
}

namespace Magento\Framework\Stdlib\DateTime;

if (!interface_exists(TimezoneInterface::class)) {
    interface TimezoneInterface
    {
        public function date(\DateTime $date): \DateTimeInterface;
    }
}

namespace Magento\Framework\App;

if (!interface_exists(RequestInterface::class)) {
    interface RequestInterface
    {
        public function getParam(string $key, mixed $default = null): mixed;
    }
}

namespace Magento\Framework\Session;

if (!interface_exists(SessionManagerInterface::class)) {
    interface SessionManagerInterface
    {
        public function getData(string $key = '', bool $clear = false): mixed;
        public function setData(string $key, mixed $value): self;
    }
}

namespace Magento\Framework\Event;

if (!interface_exists(ManagerInterface::class)) {
    interface ManagerInterface
    {
        public function dispatch(string $name, array $data = []): void;
    }
}

namespace Magento\Framework\DB\Adapter;

if (!interface_exists(AdapterInterface::class)) {
    interface AdapterInterface
    {
        public function beginTransaction(): void;
        public function commit(): void;
        public function rollBack(): void;
        public function getConnection(): AdapterInterface;
        public function isConnectionAvailable(): bool;
        public function query($sql, $bind = []): mixed;
        public function fetchAll($sql, $bind = [], $fetchMode = null): array;
        public function fetchRow($sql, $bind = [], $fetchMode = null);
        public function fetchAssoc($sql, $bind = []);
        public function fetchCol($sql, $bind = []);
        public function fetchOne($sql, $bind = []);
        public function fetchPairs($sql, $bind = []);
        public function insert($table, array $data);
        public function insertMultiple($table, array $data);
        public function insertOnDuplicate($table, array $data, array $fields = []);
        public function insertFromSelect(\Magento\Framework\DB\Select $select, $table, array $fields = [], $mode = false);
        public function delete($table, $where = '');
        public function update($table, array $bind, $where = '');
        public function lastInsertId($tableName = null, $primaryKey = null);
        public function quote($value, $type = null);
        public function quoteInto($text, $value, $type = null, $count = null);
        public function quoteIdentifier($ident, $auto = false);
        public function quoteColumnAs($ident, $alias, $auto = false);
        public function quoteTableAs($ident, $alias = null, $auto = false);
        public function describeTable($tableName, $schemaName = null);
        public function createTable(\Magento\Framework\DB\Ddl\Table $table);
        public function dropTable($tableName, $schemaName = null);
        public function truncateTable($tableName, $schemaName = null);
        public function addColumn($tableName, $columnName, $definition);
        public function dropColumn($tableName, $columnName, $schemaName = null);
        public function changeColumn($tableName, $oldColumnName, $newColumnName, $definition);
        public function modifyColumn($tableName, $columnName, $definition);
        public function showTableStatus($tableName, $schemaName = null);
        public function getCreateTable($tableName, $schemaName = null);
        public function getForeignKeyByTable($tableName, $schemaName = null);
        public function getForeignKeys($tableName, $schemaName = null);
        public function createTableByDdl($tableName, $newTableName);
        public function resetDdlCache($tableName = null, $schemaName = null);
        public function select();
        public function fetchAllByHash($value, $tableName, ...$args);
        public function getTableName($tableName);
        public function getTriggerName($tableName, $time, $event);
        public function changeTableEngine($tableName, $engine);
        public function changeTableComment($tableName, $comment);
        public function getFullTextIndex($tableName, $schemaName = null);
        public function getTableEngine($tableName, $schemaName = null);
        public function getTableComment($tableName, $schemaName = null);
        public function getIfnullSql($expression, $value = 0);
        public function getCaseSql($valueName, $casesResults, $defaultValue = null);
        public function getConcatSql(array $data, $separator = null);
        public function getLengthSql($string);
        public function getLeastSql(array $data);
        public function getGreatestSql(array $data);
        public function getDateAddSql($date, $interval, $unit);
        public function getDateSubSql($date, $interval, $unit);
        public function getDateFormatSql($date, $format);
        public function getDatePartSql($date);
        public function getSubstringSql($stringExpression, $pos, $len = null);
        public function getStandardDeviationSql($expressionField);
        public function getDateExtractSql($date, $unit);
        public function getTableChecksum($tableName, $schemaName = null): int;
        public function supportStraightJoin(): bool;
        public function orderRand(\Magento\Framework\DB\Select $select, $field = null): void;
        public function forUpdate($sql): string;
        public function getPrimaryKeyName(\Magento\Framework\DB\Ddl\Table $table): string;
        public function decodeDateField($expression): string;
        public function getTransactionLevel(): int;
        public function getIndexList($tableName, $schemaName = null): array;
    }
}

namespace Magento\Framework\DB\Ddl;

if (!class_exists(Table::class)) {
    class Table {}
}

namespace Magento\Framework\DB;

if (!class_exists(Select::class)) {
    class Select
    {
        public function __construct(?\Magento\Framework\DB\Adapter\AdapterInterface $adapter = null) {}
        public function from($name, $cols = '*', $schema = null): self { return $this; }
        public function where($cond, $value = null, $type = null): self { return $this; }
    }
}

namespace Magento\Framework\Api;

if (!interface_exists(SearchResultsInterface::class)) {
    interface SearchResultsInterface
    {
        public function getItems(): array;
        public function setItems(array $items): self;
        public function getSearchCriteria(): SearchCriteriaInterface;
        public function setSearchCriteria(SearchCriteriaInterface $searchCriteria): self;
        public function getTotalCount(): int;
        public function setTotalCount(int $count): self;
    }
}

if (!class_exists(SearchResults::class)) {
    class SearchResults implements SearchResultsInterface
    {
        private array $items = [];
        private ?SearchCriteriaInterface $searchCriteria = null;
        private int $totalCount = 0;

        public function getItems(): array { return $this->items; }
        public function setItems(array $items): SearchResultsInterface { $this->items = $items; return $this; }
        public function getSearchCriteria(): SearchCriteriaInterface { return $this->searchCriteria; }
        public function setSearchCriteria(SearchCriteriaInterface $searchCriteria): SearchResultsInterface { $this->searchCriteria = $searchCriteria; return $this; }
        public function getTotalCount(): int { return $this->totalCount; }
        public function setTotalCount(int $count): SearchResultsInterface { $this->totalCount = $count; return $this; }
    }
}

if (!interface_exists(SearchCriteriaInterface::class)) {
    interface SearchCriteriaInterface
    {
        public function getFilterGroups(): array;
        public function setFilterGroups(array $filterGroups): self;
        public function getCurrentPage(): ?int;
        public function setCurrentPage(?int $page): self;
        public function getPageSize(): ?int;
        public function setPageSize(?int $size): self;
    }
}

if (!class_exists(Filter::class)) {
    class Filter
    {
        public function getField(): string { return ''; }
        public function setField(string $field): self { return $this; }
        public function getValue(): mixed { return null; }
        public function setValue(mixed $value): self { return $this; }
        public function getConditionType(): ?string { return null; }
        public function setConditionType(?string $conditionType): self { return $this; }
    }
}

if (!class_exists(FilterGroup::class)) {
    class FilterGroup
    {
        public function getFilters(): array { return []; }
        public function setFilters(array $filters): self { return $this; }
    }
}

namespace Magento\Framework\Exception;

if (!class_exists(LocalizedException::class)) {
    class LocalizedException extends \Exception {}
}

if (!class_exists(NoSuchEntityException::class)) {
    class NoSuchEntityException extends \Exception {}
}

if (!class_exists(CouldNotSaveException::class)) {
    class CouldNotSaveException extends \Exception {}
}

if (!class_exists(CouldNotDeleteException::class)) {
    class CouldNotDeleteException extends \Exception {}
}

namespace Magento\Framework\Api;

abstract class AbstractSimpleObject {}
abstract class SearchCriteria {}
if (!interface_exists(SearchResultsInterfaceFactory::class)) {
    interface SearchResultsInterfaceFactory
    {
        public function create(): SearchResultsInterface;
    }
}

namespace Magento\Framework\ObjectManager;

if (!interface_exists(ObjectManagerInterface::class)) {
    interface ObjectManagerInterface
    {
        public function create(string $type, array $arguments = []): mixed;
        public function get(string $type, array $arguments = []): mixed;
    }
}
