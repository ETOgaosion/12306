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
        return Database::selectAll("select * from get_train_bt_cities('{$start_city}', '{$end_city}', '{$start_date}', '{$start_time}', false) dirc_tr_btc order by dirc_tr_btc.seat_prices[1] DESC, dirc_tr_btc.seat_prices[2] DESC, dirc_tr_btc.seat_prices[3] DESC, dirc_tr_btc.seat_prices[4] DESC, dirc_tr_btc.seat_prices[5] DESC, dirc_tr_btc.seat_prices[6] DESC, dirc_tr_btc.seat_prices[7] DESC, dirc_tr_btc.durance ASC, dirc_tr_btc.leave_time ASC");
    }

    public static function querytrain($train_num, $start_date): array
    {
        return Database::selectAll("select * from get_train_info({$train_num}, {$start_date})");
    }
}