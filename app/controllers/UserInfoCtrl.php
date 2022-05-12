<?php

namespace app\controllers;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

use app\models\AdminQuery;
use app\models\UserInfo;
use app\controllers\ViewCtrl;
use DateTime;
use JetBrains\PhpStorm\NoReturn;
use app\tools\Session;

class UserInfoCtrl
{
    #[NoReturn] public static function queryAll(): void
    {
        $userName = Session::get('userName');
        $uid = Session::get('uid');
        $isAdmin = Session::get('isAdmin');
        $resArray = UserInfo::userQueryAllInfo($uid);
        ViewCtrl::includeView('/userSpace', array(
            'userName' => $userName,
            'userEmail' => $resArray['user_email'] ?? '',
            'userRealName' => $resArray['user_real_name'] ?? '',
            'userTelNum' => $resArray['user_telnum'] ?? ''
        ));
        die();
    }

    #[NoReturn] public static function queryOrder(): void
    {
        $uid = Session::get('uid');
        $startQueryDate = $_POST['inputStartQueryDate'];
        $endQueryDate = $_POST['inputEndQueryDate'];
        $resArray = UserInfo::userQueryOrder($uid, $startQueryDate, $endQueryDate);
        Session::set('userQueryResArray', $resArray);
        self::queryAll();
    }

    #[NoReturn] public static function cancelOrder(): void
    {
        $oid = $_GET['oid'];
        UserInfo::userCancelOrder($oid);
        $startDate = (new DateTime())->setTimestamp(0);
        $startDate = $startDate->format('Y-m-d');
        $endDate = time();
        $endDate = date('Y-m-d', strtotime('+14 days') + $endDate);
        $_POST['inputStartQueryDate'] = $startDate;
        $_POST['inputEndQueryDate'] = $endDate;
        self::queryOrder();
    }
}