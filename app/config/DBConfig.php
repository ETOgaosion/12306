<?php
namespace  app\config;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

class DBConfig
{
    private static string $pgConnectionString = "host=localhost port=5432 dbname=postgres user=gzy password=123456";

    public static function getPgConnectionString(): string {
        return  self::$pgConnectionString;
    }
}