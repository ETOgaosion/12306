<?php

namespace app\controllers;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

use app\models\UserOrder;

class ThreadCtrl
{
    public static function start_cancel_preorder_thread() {
        while (true) {
            UserOrder::removeOutDateOrder();
            sleep(30);
        }
    }
}