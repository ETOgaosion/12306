<?php
namespace  app\controllers;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

use app\controllers\ViewCtrl;
use app\models\AdminQuery;

class AdminCtrl
{
    public static function adminQueryAll() {
        $resArray = AdminQuery::adminQueryOrders();
        $totalOrder = $resArray['total_order_num'];
        $totalPrice = $resArray['total_price'];
        $hotTrains = $resArray['hot_trains'];
        $resArray = AdminQuery::adminQueryUsers();
        ViewCtrl::includeView('/adminMain', array(
            'totalOrder' => $totalOrder,
            'totalPrice' => $totalPrice,
            'hotTrains' => $hotTrains,
            'registerUserIdList' => array_column($resArray, 'p_pid'),
            'registerUserNameList' => array_column($resArray, 'uname')
        ));
    }

    public static function adminRefreshOrders(): void
    {
        $resArray = AdminQuery::adminQueryOrders();
        Session::set('totalOrder', $resArray['total_order_num']);
        Session::set('totalPrice', $resArray['total_price']);
        Session::set('hotTrains', $resArray['hot_trains']);
    }

    public static function adminQueryUserInfo($uname): void
    {
         Session::set('adminQueryUserInfoResArray', AdminQuery::queryUserInfo($uname));
    }

    public static function adminQueryUserOrders($uname): void
    {
        $resArray = AdminQuery::queryUserOrders($uname);
        Session::set('adminQueryUserOrdersResArray', $resArray);
    }

    public static function adminQueryUser(): void
    {
        $uname = $_POST['queryUserNameInput'];
        self::adminQueryUserOrders($uname);
        self::adminQueryUserInfo($uname);
    }
}