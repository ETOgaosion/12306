<?php

namespace app\controllers;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}


use app\models\UserOrder;
use app\models\UserInfo;
use app\controllers\ViewCtrl;
use app\tools\Session;
use JetBrains\PhpStorm\NoReturn;

class OrderCtrl
{
    #[NoReturn] public static function generateOrder(): void
    {
        UserOrder::removeOutDateOrder();
        $trainIdList = array($_GET["trainId"]);
        $trainNameList = array($_GET["trainName"]);
        $stationFromIdList = array($_GET["stationFromId"]);
        $stationFromList = array($_GET["stationFrom"]);
        $stationToIdList = array($_GET["stationToId"]);
        $stationToList = array($_GET["stationTo"]);
        $seat_typeList = array($_GET["seat_type"]);
        $order_dateList = array($_GET["order_date"]);
        $remainSeatsArrayList = array();
        for ($i = 0; $i < count($trainNameList); $i++) {
            $remainSeatsArrayList[] = UserOrder::getRemainTickets($trainIdList[$i], $order_dateList[$i], $stationFromIdList[$i], $stationToIdList[$i], $seat_typeList[$i]);
        }
        $remainSeatList = array_column($remainSeatsArrayList,'seat_num');
        $resArray = UserInfo::userQueryAllInfo($_SESSION["uid"]);
        ViewCtrl::includeView('/userOrderGenerate', array(
            'trainIdList' => $trainIdList,
            'trainNameList' => $trainNameList,
            'dateList' => $order_dateList,
            'start_stationList' => $stationFromList,
            'startStationIdList' => $stationFromIdList,
            'end_stationList' => $stationToList,
            'endStationIdList' => $stationToIdList,
            'seatTypeList' => $seat_typeList,
            'remain_ticketsList' => $remainSeatList,
            'userName' => $_SESSION["userName"],
            'userRealName' => $resArray["user_real_name"],
            'userTelNum' => $resArray["user_telnum"]
        ));
        die();
    }

    #[NoReturn] public static function generateOrderByPost(): void
    {
        UserOrder::removeOutDateOrder();
        $count = $_POST["count"];
        $trainIdList = array();
        $trainNameList = array();
        $stationFromIdList = array();
        $stationFromList = array();
        $stationToIdList = array();
        $stationToList = array();
        $seat_typeList = array();
        $order_dateList = array();
        $remainSeatsArrayList = array();
        for($i = 0; $i < $count; $i++) {
            for($j = 0; $j < 7; $j++) {
                if (array_key_exists("seat-type-{$i}-{$j}-check", $_POST) && $_POST["seat-type-{$i}-{$j}-check"] == 'Yes'){
                    $trainIdList[] = $_POST["seat-type-{$i}-{$j}-trainId"];
                    $trainNameList[] = $_POST["seat-type-{$i}-{$j}-trainName"];
                    $stationFromIdList[] = $_POST["seat-type-{$i}-{$j}-stationFromId"];
                    $stationFromList[] = $_POST["seat-type-{$i}-{$j}-stationFrom"];
                    $stationToIdList[] = $_POST["seat-type-{$i}-{$j}-stationToId"];
                    $stationToList[] = $_POST["seat-type-{$i}-{$j}-stationTo"];
                    $seat_typeList[] = $_POST["seat-type-{$i}-{$j}-seat_type"];
                    $order_dateList[] = $_POST["seat-type-{$i}-{$j}-order_date"];
                }
            }
        }
        for ($i = 0; $i < count($trainNameList); $i++) {
            $remainSeatsArrayList[] = UserOrder::getRemainTickets($trainIdList[$i], $order_dateList[$i], $stationFromIdList[$i], $stationToIdList[$i], $seat_typeList[$i]);
        }
        $remainSeatList = array_column($remainSeatsArrayList,'seat_num');
        $resArray = UserInfo::userQueryAllInfo($_SESSION["uid"]);
        ViewCtrl::includeView('/userOrderGenerate', array(
            'trainIdList' => $trainIdList,
            'trainNameList' => $trainNameList,
            'dateList' => $order_dateList,
            'start_stationList' => $stationFromList,
            'startStationIdList' => $stationFromIdList,
            'end_stationList' => $stationToList,
            'endStationIdList' => $stationToIdList,
            'seatTypeList' => $seat_typeList,
            'remain_ticketsList' => $remainSeatList,
            'userName' => $_SESSION["userName"],
            'userRealName' => $resArray["user_real_name"],
            'userTelNum' => $resArray["user_telnum"]
        ));
        die();
    }

    #[NoReturn] public static function preorderTrain(): void
    {
        UserOrder::removeOutDateOrder();
        $count = $_POST["count"];
        $trainIdList = array();
        $trainNameList = array();
        $dateList = array();
        $stationFromIdList = array();
        $stationFromList = array();
        $stationToIdList = array();
        $stationToList = array();
        $seatTypeList = array();
        for ($i = 0; $i < $count; $i++) {
            $trainIdList[] = $_POST["trainId-{$i}"];
            $trainNameList[] = $_POST["trainName-{$i}"];
            $dateList[] = $_POST["date-{$i}"];
            $stationFromIdList[] = $_POST["stationFromId-{$i}"];
            $stationFromList[] = $_POST["stationFrom-{$i}"];
            $stationToIdList[] = $_POST["stationToId-{$i}"];
            $stationToList[] = $_POST["stationTo-{$i}"];
            $seatTypeList[] = $_POST["seatType-{$i}"];
        }
        $userNameArrayList = array();
        $userRealNameArrayList = array();
        $userTelNumListList = array();
        for ($iList = 0; ; $iList++) {
            if (array_key_exists('userName' . strval($iList), $_POST)) {
                $userNameArrayList[] = $_POST['userName' . strval($iList)];
                $userRealNameArrayList[] = $_POST['userRealName' . strval($iList)];
                $userTelNumListList[] = $_POST['userTelNum' . strval($iList)];
            } else {
                break;
            }
        }
        $succeedList = array();
        $seatIdList = array();
        $orderIdList = array();
        for($i = 0; $i < $count; $i++) {
            $resList = UserOrder::preorderTrain($trainIdList[$i], $stationFromIdList[$i], $stationToIdList[$i], $seatTypeList[$i], $dateList[$i], $userNameArrayList);
            $succeedList[] = $resList['succeed'];
            $seatIdList[] = $resList['seat_id'];
            $orderIdList[] = $resList['order_id'];
        }
        if (in_array(false, $succeedList)) {
            Session::set('preorderFailed', true);
            ViewCtrl::includeIndex();
        } else {
            ViewCtrl::includeView('/userOrderConfirm', array(
                'trainIdList' => $trainIdList,
                'trainNameList' => $trainNameList,
                'start_stationList' => $stationFromList,
                'startStationIdList' => $stationFromIdList,
                'end_stationList' => $stationToList,
                'endStationIdList' => $stationToIdList,
                'userNameList' => $userNameArrayList,
                'dateList' => $dateList,
                'userRealNameList' => $userRealNameArrayList,
                'userTelNumList' => $userTelNumListList,
                'orderIdList' => $orderIdList,
                'seatNumsList' => $seatIdList,
            ));
        }
        die();
    }

    #[NoReturn] public static function orderTrain(): void
    {
        UserOrder::removeOutDateOrder();
        $orderIdList = $_POST["orderId"];
        $uidNum = $_POST["uidNum"];
        for($i = 0; $i < count($orderIdList); $i++) {
            $resList[] = UserOrder::orderTrain($orderIdList[$i], $uidNum);
            if (in_array(false, $resList[$i])) {
                Session::set('orderFailed', true);
                Session::set('orderSucceed', false);
                Session::set('orderStatus', $resList[$i]);
                ViewCtrl::includeIndex();
                die();
            }
        }
        Session::set('orderFailed', false);
        Session::set('orderSucceed', true);
        ViewCtrl::includeIndex();
        die();
    }
}