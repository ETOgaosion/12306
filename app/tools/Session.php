<?php
namespace  app\tools;

use app\config\SessionConfig;

class Session
{
    public static function init(): void
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_name('PHPSESSID');
            session_set_cookie_params(SessionConfig::getSessionLifeTimeOrOptions(), SessionConfig::getSessionCookiePath());
            session_save_path(SessionConfig::getSessionSavePath());
            session_start();
            self::unsetAll();
        }

        register_shutdown_function(function() {
            if (session_status() != PHP_SESSION_ACTIVE) {
                session_unset();
                session_destroy();
            }
        });
    }

    public static function set($name, $value): void
    {
        $_SESSION[$name] = $value;
    }

    public static function unset($name): void
    {
        unset($_SESSION[$name]);
    }

    public static function unsetAll(): void
    {
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
    }

    public static function get($name) {
        return $_SESSION[$name];
    }

    public static function isSet($name): bool
    {
        return isset($_SESSION[$name]);
    }
}