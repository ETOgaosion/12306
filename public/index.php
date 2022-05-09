<?php

define('PAGE_START', microtime(true));
const PHP_BASE_DIR = __DIR__ . '/..';
const PHP_APP_DIR = PHP_BASE_DIR . '/app';
const PHP_VIEW_DIR = __DIR__ . '/views';
const PHP_INDEX_DIR = __DIR__;

require PHP_APP_DIR . '/bootstrap/init.php';

use routes\Route;

$request = array(
    'method' => $_SERVER['REQUEST_METHOD'],
    'uri' => $_SERVER['REQUEST_URI'],
    'path' => Route::getRequestPath($_SERVER['REQUEST_URI']),
    'time' => $_SERVER['REQUEST_TIME'],
);

Route::dispatch($request);