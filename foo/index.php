<?php

foo_autoloader_init(); // let's not require() everything manually

$environment = 'dev'; // abracadabra. Normally, we would infer this, or set it in a local file not present in Git.

// we are passing in a string which specifies the environment - again, there are more elegant ways to do this.
$config = new \foo\config\LocalConfig($environment);

// NOTE: the two undefined variables would normally be provided by the framework
$controller = new \foo\controllers\ProductController($config, $esDriver, $mysqlDriver);

$productId = 'lorem-ipsum';
echo $controller->detailAction($productId);

exit;

// yeah, yeah, inline function. This simulates an autoloader otherwise provided by a framework.
function foo_autoloader_init() {
    // a quick-and dirty PSR-4 autoloader
    /** @link: http://www.php-fig.org/psr/psr-4/examples/ */
    spl_autoload_register(function ($class) {

        // project-specific namespace prefix
        $prefix = 'foo\\';

        // base directory for the namespace prefix
        $base_dir = __DIR__ . '/';

        // does the class use the namespace prefix?
        $len = strlen($prefix);
        if (0 !== strncmp($prefix, $class, $len)) {
            // no, move to the next registered autoloader
            return;
        }

        // get the relative class name
        $relative_class = substr($class, $len);

        // replace the namespace prefix with the base directory, replace namespace
        // separators with directory separators in the relative class name, append
        // with .php
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        // if the file exists, require it
        if (file_exists($file)) {
            /** @noinspection PhpIncludeInspection */
            require_once $file;
        }
    });
}