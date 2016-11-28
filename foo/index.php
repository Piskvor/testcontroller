<?php

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
        require $file;
    }
});

// we are passing in a string which specifies the environment
$config = new \foo\config\LocalConfig('dev');

// NOTE: these two undefined variables would be provided by the framework
/** @noinspection PhpUndefinedVariableInspection */
$controller = new \foo\controllers\ProductController($config, $esDriver, $mysqlDriver);

echo $controller->detailAction('lorem-ipsum');