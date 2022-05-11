<?php
namespace  app\controllers;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

use app\models\UserInfo;
use app\controllers\ViewCtrl;
use JetBrains\PhpStorm\NoReturn;

class UserInfoCtrl
{
    #[NoReturn] public static function queryAll(): void
    {
        $userName = $_SESSION['userName'];
        $uid = $_SESSION['uid'];
        $isAdmin = $_SESSION['isAdmin'];
        $resArray = UserInfo::userQueryAllInfo($uid);
        ViewCtrl::includeView('/userSpace', array(
            'userName' => $userName,
            'userEmail' => $resArray['user_email'] ?? '',
            'userRealName' => $resArray['user_real_name'] ?? '',
            'userTelNum' => $resArray['user_telnum'] ?? ''
        ));
        die();
    }

    public static function queryOrder(): void
    {
        $uid = $_SESSION['uid'];
        $startQueryDate = $_POST['inputStartQueryDate'];
        $endQueryDate = $_POST['inputEndQueryDate'];
        $resArray = UserInfo::userQueryOrder($uid, $startQueryDate, $endQueryDate);
        $_SESSION['userQueryResArray'] = $resArray;
        self::queryAll();
    }

    public static function cancelOrder(): void
    {
        $oid = $_GET['oid'];
        UserInfo::userCancelOrder($oid);
    }
}