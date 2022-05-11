<?php

namespace app\controllers;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}


class ThreadCtrl
{
    public static function start_cancel_preorder_thread() {
        while (true) {

        }
    }
}