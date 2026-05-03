<?php
declare(strict_types=1);

if (!defined('TESTS_BOOTSTRAPPED')) {
    define('TESTS_BOOTSTRAPPED', true);
}

$ds = DIRECTORY_SEPARATOR;
$vendorAutoload = dirname(__DIR__, 6) . $ds . 'vendor' . $ds . 'autoload.php';
if (file_exists($vendorAutoload)) {
    require_once $vendorAutoload;
}

if (!class_exists(\Magento\Framework\App\Helper\AbstractHelper::class)) {
    require_once __DIR__ . '/Stub/FrameworkStubs.php';
}

if (!class_exists(\Ronald2Wing\Forum\Model\ForumFactory::class)) {
    require_once __DIR__ . '/Stub/FactoryStubs.php';
}

$moduleBase = dirname(__DIR__, 2);

spl_autoload_register(static function (string $class) use ($moduleBase, $ds): void {
    if (str_starts_with($class, 'Ronald2Wing\\Forum\\')) {
        $relative = substr($class, 17);
        $parts = explode('\\', $relative);
        $file = $moduleBase . $ds . implode($ds, $parts) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

if (!function_exists('__')) {
    function __(string $text): string
    {
        return $text;
    }
}
