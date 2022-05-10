<?php
namespace app\config;

class SessionConfig
{
    private static int $sessionLifeTimeOrOptions = 60 * 60 * 24 * 30;
    private static string $sessionCookiePath = PHP_BASE_DIR . '/var/cache/sessionCookie';
    private static string $sessionSavePath = PHP_BASE_DIR . '/var/cache/session';

    public static function getSessionLifeTimeOrOptions(): int {
        return self::$sessionLifeTimeOrOptions;
    }

    public static function getSessionCookiePath(): string {
        return self::$sessionCookiePath;
    }

    public static function getSessionSavePath(): string {
        return self::$sessionSavePath;
    }
}