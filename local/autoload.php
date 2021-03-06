<?php
namespace local;

spl_autoload_register(function ($class) {
    if (strpos($class, 'local\\') === 0) {
        $class = substr($class, 6);
        $classFile = realpath(__DIR__ . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php');
        if (file_exists($classFile)) {
            require ($classFile);
        }
        unset($classFile);
    }
});
        