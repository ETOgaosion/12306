<?php

namespace app\controllers;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}


use app\models\UserOrder;
use app\models\UserInfo;
use app\controllers\ViewCtrl;
use JetBrains\PhpStorm\NoReturn;

class OrderCtrl
{
    #[NoReturn] public static function generateOrder(): void
    {
        $trainId = $_GET['trainId'];
        $trainName = $_GET['trainName'];
        $stationFromId = $_GET['stationFromId'];
        $stationFrom = $_GET['stationFrom'];
        $stationToId = $_GET['stationToId'];
        $stationTo = $_GET['stationTo'];
        $seat_type = $_GET['seat_type'];
        $order_date = $_GET['order_date'];
        $remainSeatsArray = UserOrder::getRemainTickets($trainId, $order_date, $stationFromId, $stationToId, $seat_type);
        $remainSeat = $remainSeatsArray['seat_num'];
        $resArray = UserInfo::userQueryAllInfo($_SESSION['uid']);
        ViewCtrl::includeView('/userOrderGenerate', array(
            'trainId' => $trainId,
            'trainName' => $trainName,
            'date' => $order_date,
            'start_station' => $stationFrom,
            'startStationId' => $stationFromId,
            'end_station' => $stationTo,
            'endStationId' => $stationToId,
            'seatType' => $seat_type,
            'remain_tickets' => $remainSeat,
            'userName' => $_SESSION['userName'],
            'userRealName' => $resArray['user_real_name'],
            'userTelNum' => $resArray['user_telnum']
        ));
        die();
    }

    #[NoReturn] public static function preorderTrain(): void
    {
        $trainId = $_POST['trainId'];
        $trainName = $_POST['trainName'];
        $date = $_POST['date'];
        $stationFromId = $_POST['stationFromId'];
        $stationFrom = $_POST['stationFrom'];
        $stationToId = $_POST['stationToId'];
        $stationTo = $_POST['stationTo'];
        $seatType = $_POST['seatType'];
        $userNameArray = array();
        $userRealNameArray = array();
        $userTelNumList = array();
        for ($i = 0; ; $i++) {
            if (array_key_exists('userName'.strval($i),$_POST)) {
                $userNameArray[] = $_POST['userName' . strval($i)];
                $userRealNameArray[] = $_POST['userRealName'. strval($i)];
                $userTelNumList[] = $_POST['userTelNum'. strval($i)];
            }
            else {
                break;
            }
        }
        $res = UserOrder::preorderTrain($trainId, $stationFromId, $stationToId, $seatType, $date, $userNameArray);
        $succeed = $res['succeed'] ?? false;
        $seatId = $res['seat_id'] ?? 0;
        $orderId = $res['order_id'] ?? 0;
        if (!$succeed) {
            $_SESSION['preorderFailed'] = true;
            ViewCtrl::includeIndex();
            die();
        }
        else {
            ViewCtrl::includeView('/userOrderConfirm', array(
                'trainId' => $trainId,
                'trainName' => $trainName,
                'date' => $date,
                'start_station' => $stationFrom,
                'startStationId' => $stationFromId,
                'end_station' => $stationTo,
                'endStationId' => $stationToId,
                'seatType' => $seatId,
                'userNameList' => $userNameArray,
                'userRealNameList' => $userRealNameArray,
                'userTelNumList' => $userTelNumList,
                'orderId' => $orderId
            ));
            die();
        }
    }

    #[NoReturn] public static function orderTrain(): void
    {
        $orderId = $_POST['orderId'];
        $uidNum = $_POST['uidNum'];
        $res = UserOrder::orderTrain($orderId, $uidNum);
        if (in_array(false, $res)) {
            $_SESSION['orderFailed'] = true;
            $_SESSION['orderSucceed'] = false;
            $_SESSION['orderStatus'] = $res;
        }
        else {
            $_SESSION['orderFailed'] = false;
            $_SESSION['orderSucceed'] = true;
        }
        ViewCtrl::includeIndex();
        die();
    }
}