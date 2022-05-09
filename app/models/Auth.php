<?php
namespace models;

use app\tools\Session;

class Auth
{
    private static $currentUser;

    public static function login($userName): bool
    {
        self::$currentUser = new UserInfo;
        if (self::$currentUser->readFromDatabase($userName)) {
            Session::set('userName', $userName);
            return true;
        }
        Session::unset('userName');
        self::$currentUser = null;
        return false;
    }

    public static function logout(): void
    {
        Session::unset('userName');
        self::$currentUser = null;
    }
}