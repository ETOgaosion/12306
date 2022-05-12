<?php

namespace app\controllers;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

use app\controllers\ViewCtrl;
use app\models\AdminQuery;
use app\models\UserOrder;
use app\tools\Session;

class AdminCtrl
{
    public static function adminQueryAll(): void
    {
        UserOrder::removeOutDateOrder();
        $resArray = AdminQuery::adminQueryOrders();
        $totalOrder = $resArray['total_order_num'];
        $totalPrice = $resArray['total_price'];
        $hotTrains = $resArray['hot_trains'];
        $resArray = AdminQuery::adminQueryUsers();
        ViewCtrl::includeView('/adminMain', array(
            'totalOrder' => $totalOrder,
            'totalPrice' => $totalPrice,
            'hotTrains' => $hotTrains,
            'resArray' => $resArray,
            'registerUserIdList' => array_column($resArray, 'uid'),
            'registerUserNameList' => array_column($resArray, 'uname')
        ));
    }

    public static function adminRefreshOrders(): void
    {
        UserOrder::removeOutDateOrder();
        $resArray = AdminQuery::adminQueryOrders();
        Session::set('totalOrder', $resArray['total_order_num']);
        Session::set('totalPrice', $resArray['total_price']);
        Session::set('hotTrains', $resArray['hot_trains']);
    }

    public static function adminQueryUserInfo($uname): void
    {
        UserOrder::removeOutDateOrder();
        Session::set('adminQueryUserInfoResArray', AdminQuery::queryUserInfo($uname));
    }

    public static function adminQueryUserOrders($uname): void
    {
        UserOrder::removeOutDateOrder();
        $resArray = AdminQuery::queryUserOrders($uname);
        Session::set('adminQueryUserOrdersResArray', $resArray);
    }

    public static function adminQueryUser(): void
    {
        UserOrder::removeOutDateOrder();
        $uname = $_GET['userName'];
        self::adminQueryUserOrders($uname);
        self::adminQueryUserInfo($uname);
        self::adminQueryAll();
    }
}