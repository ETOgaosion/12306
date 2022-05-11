<?php
namespace  app\models;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

use app\database\interfaces\Database;

class UserQuery
{
    public static function queryCity($start_city, $end_city, $start_date, $start_time): array
    {
        return Database::selectAll("select * from get_train_bt_cities('{$start_city}', '{$end_city}', '{$start_date}', '{$start_time}', true)");
    }

    public static function querytrain($train_num, $start_date): array
    {
        return Database::selectAll("select * from get_train_info({$train_num}, {$start_date})");
    }
}