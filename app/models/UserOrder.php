<?php

namespace  app\models;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

const SEAT_TYPE_ENUM = array(
    'YZ', 'RZ', 'YW_S', 'YW_Z', 'YW_X', 'RW_S', 'RW_X'
);

use app\database\interfaces\Database;

class UserOrder
{
    public static function getRemainTickets($trainId, $queryDate, $stationFromId, $stationToId, $seatType): bool|array
    {
        $seatTypeStr = SEAT_TYPE_ENUM[$seatType];
        return Database::selectFirst("select * from get_min_seats({$trainId}, '{$queryDate}', {$stationFromId}, {$stationToId}, '{{$seatTypeStr}}')");
    }

    public static function preorderTrain($trainId, $stationFromId, $stationToId, $seatType, $date, $userNameArray): bool|array
    {
        $seatTypeStr = SEAT_TYPE_ENUM[$seatType];
        $userNameArrayStr = "'{";
        foreach ($userNameArray as $uname ) {
            $userNameArrayStr = $userNameArrayStr . "{$uname},";
        }
        $c = count($userNameArray);
        $userNameArrayStr = substr($userNameArrayStr, 0, strlen($userNameArrayStr) - 1)."}'";
        return Database::selectFirst("select * from pre_order_train({$trainId}, {$stationFromId}, {$stationToId}, '{$seatTypeStr}', {$c}, '{$date}', {$userNameArrayStr})");
    }

    public static function orderTrain($orderId, $uidNum): bool|array
    {
        return Database::selectFirst("select * from order_train_seats({$orderId}, {$uidNum})");
    }

    public static function removeOutDateOrder(): void
    {
        Database::selectFirst("select remove_outdated_order()");
    }
}