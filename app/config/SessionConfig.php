<?php
namespace  app\config;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

class SessionConfig
{
    private static int $sessionLifeTimeOrOptions = 3600 * 24 * 30;
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