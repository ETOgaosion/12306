<?php
namespace  app\database\interfaces;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

use app\config\DBConfig;
use JetBrains\PhpStorm\NoReturn;

class Database
{
    private static $pgConnection;

    #[NoReturn] public static function init(): void
    {
        self::$pgConnection = pg_connect(DBConfig::getPgConnectionString())
        or die("There is something wrong with database...");
    }

    public static function escape($string): string
    {
        return pg_escape_string(self::$pgConnection, $string);
    }

    public static function query($querySQL): bool
    {
        return pg_query(self::$pgConnection, $querySQL);
    }

    public static function fetchRow($res, $row = null, $result_type = PGSQL_NUM): bool|array
    {
        return pg_fetch_row($res, $row, $result_type);
    }

    public static function selectFirst($querySQL, $opt = PGSQL_ASSOC): bool|array
    {
        $res = pg_query(self::$pgConnection, $querySQL);
        if (pg_num_rows($res) > 0) {
            return pg_fetch_array($res, 0, $opt);
        }
        return false;
    }

    public static function selectAll($querySQL): array
    {
        $res = pg_query(self::$pgConnection, $querySQL);
        $arr = array();
        while($row = pg_fetch_assoc($res)) {
            $arr []= $row;
        }
        return $arr;
    }
}