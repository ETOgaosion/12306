<?php
namespace  app\models;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

use app\database\interfaces\Database;

class UserInfo
{
    public static function userQueryAllInfo($uid): bool|array
    {
        return Database::selectFirst("select * from user_query_info({$uid})");
    }

    public static function userQueryOrder($uid, $startQueryDate, $endQueryDate): array
    {
        return Database::selectAll("select * from user_query_order({$uid}, '{$startQueryDate}', '{$endQueryDate}')");
    }

    public static function userCancelOrder($oid): bool|array
    {
        return Database::selectFirst("select * from user_cancel_order({$oid})");
    }
}