<?php
namespace  app\config;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

class DBConfig
{
    private static string $pgConnectionString = "dbname=postgres user=gzy password=955885 host=localhost";

    public static function getPgConnectionString(): string {
        return  self::$pgConnectionString;
    }
}