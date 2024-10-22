<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('anjumWpTaskAutoload')) {
    function anjumWpTaskAutoload($class)
    {
        // Do not load unless in plugin domain.
        $namespace = 'anjumWpTask';
        if (strpos($class, $namespace) !== 0) {
            return;
        }

        // Converts Class_Name (class convention) to class-name (file convention).

        // Remove the root namespace.
        $classWithoutNamespace = substr($class, strlen($namespace));

        // Build the file path.
        $file_path = str_replace('\\', DIRECTORY_SEPARATOR, $classWithoutNamespace);

        $file      = dirname(__FILE__) . $file_path . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
    // Register the autoloader.
    spl_autoload_register('anjumWpTaskAutoload');
}
