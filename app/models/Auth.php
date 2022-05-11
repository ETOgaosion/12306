<?php
namespace  app\models;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

use app\tools\Session;
use app\database\interfaces\Database;
use app\controllers\ViewCtrl;
use JetBrains\PhpStorm\NoReturn;

class Auth
{
    public static function userLogin($userName, $userPassword): array
    {
        return Database::selectFirst("select * from passenger_login('{$userName}', '{$userPassword}')");
    }

    public static function adminLogin($userName, $userPassword, $authentication): array
    {
        return Database::selectFirst("select * from admin_login('{$userName}', '{$userPassword}', '{$authentication}')");
    }

    public static function userRegister($userName, $userPassword, $userRealName, $userTelNum, $userEmail): array
    {
        return Database::selectFirst("select * from passenger_register('{$userName}', '{$userPassword}', '{$userRealName}','{$userTelNum}', '{$userEmail}')");
    }

    public static function adminRegister($userName, $userPassword, $userTelNum, $userEmail, $auth): array
    {
        return Database::selectFirst("select * from admin_register('{$userName}', '{$userPassword}', '{$userTelNum}', '{$userEmail}', '{$auth}', 'ALL')");
    }
}