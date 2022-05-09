<?php

class ClassLoader {
    public static array $belongMap = array(
        'app' => PHP_APP_DIR,
        'config' => PHP_APP_DIR. '/config',
        'controllers' => PHP_APP_DIR. '/controllers',
        'database' => PHP_APP_DIR. '/database',
        'models' => PHP_APP_DIR. '/database/models',
        'routes' => PHP_APP_DIR. '/routes',
    );

    public static function autoload($className): void
    {
        $fileName = self::getFileName($className);
        include_once $fileName;
    }

    private static function getFileName($className): string
    {
        $belong = substr($className, 0, strpos($className, '\\'));
        $fileName = substr($className, strlen($belong)) . '.php';
        $belongDir = self::$belongMap[$belong];
        return strtr($belongDir . $fileName, '\\', '/');
    }
}