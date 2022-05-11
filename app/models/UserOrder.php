<?php

namespace  app\models;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

use app\database\interfaces\Database;

class UserOrder
{
    public static function getRemainTickets($trainId, $queryDate, $stationFromId, $stationToId, $seatType): bool|array
    {
        return Database::selectFirst("select * from get_min_seats({$trainId}, {$queryDate}, {$stationFromId}, {$stationToId}, [{$seatType}])");
    }

    public static function preorderTrain($trainId, $stationFromId, $stationToId, $seatType, $userNameArray): bool|array
    {
        $userNameArrayStr = "[";
        foreach ($userNameArray as $uname ) {
            $userNameArrayStr = $userNameArrayStr . "'{$uname},'";
        }
        $userNameArrayStr = substr($userNameArrayStr, -1)."]";
        return Database::selectFirst("select * from pre_order_train({$trainId}, {$stationFromId}, {$stationToId}, {$seatType}, {count($userNameArray)}, {$userNameArrayStr}");
    }

    public static function orderTrain($orderId, $uidNum): bool|array
    {
        return Database::selectFirst("select * from order_train_seats({$orderId}, {$uidNum})");
    }
}