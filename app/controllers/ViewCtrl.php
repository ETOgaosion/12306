<?php
namespace app\controllers;

use JetBrains\PhpStorm\NoReturn;

class ViewCtrl
{
    public static function includeView($name, $params = array()): void
    {
        extract($params);
        include PHP_VIEW_DIR.$name.'.php';
    }

    public static function includePageHeader($params = array()): void
    {
        extract($params);
        self::includeView('/shared/pageHeader', $params);
    }

    public static function includePageFooter(): void
    {
        self::includeView('/shared/pageFooter', array());
    }

    public static function includeIndex(): void
    {
        self::includeView('/index', array());
    }

    public static function includeMain(): void
    {
        if (array_key_exists('isAdmin', $_SESSION) && !$_SESSION['isAdmin']) {
            self::includeView('/userMain');
        }
        else {
            self::includeView('/adminMain');
        }
    }

    #[NoReturn] public static function errorPageNotFound(): void
    {
        http_response_code(404);
        self::includeView('/errorPage');
        die();
    }
}