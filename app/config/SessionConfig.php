<?php
namespace app\config;

class SessionConfig
{
    private static int $sessionLifeTimeOrOptions = 60 * 60 * 24 * 30;
    private static string $sessionCookiePath = '/UCAS_Database';
    private static string $sessionSavePath = '/UCAS_Database/var/cache/session';

    public static function getSessionLifeTimeOrOptions(): int {
        return self::$sessionLifeTimeOrOptions;
    }

    public static function getSessionCookiePath(): int {
        return self::$sessionCookiePath;
    }

    public static function getSessionSavePath(): int {
        return self::$sessionSavePath;
    }
}