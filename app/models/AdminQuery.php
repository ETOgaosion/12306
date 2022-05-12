<?php
namespace  app\models;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

use app\database\interfaces\Database;
use DateTime;

class AdminQuery
{
    public static function adminQueryOrders(): bool|array
    {
        return Database::selectFirst("select * from admin_query_orders()");
    }

    public static function adminQueryUsers(): array
    {
        return Database::selectAll("select * from admin_query_users()");
    }

    public static function queryUserInfo($uname): array
    {
        return Database::selectFirst("select * from admin_query_user_info('$uname')");
    }

    public static function queryUserOrders($uname): array
    {
        $startDate = (new DateTime())->setTimestamp(0);
        $startDate = $startDate->format('Y-m-d');
        $endDate = time();
        $endDate = date('Y-m-d', strtotime('+14 days') + $endDate);
        return Database::selectAll("select * from admin_query_user_orders('{$uname}', '{$startDate}', '{$endDate}')");
    }
}