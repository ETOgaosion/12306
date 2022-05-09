<?php
namespace app\config;

class DBConfig
{
    private static string $pgConnectionString = "dbname=postgres user=gzy password=955885 host=localhost";

    public static function getPgConnectionString(): string {
        return  self::$pgConnectionString;
    }
}