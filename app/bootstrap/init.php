<?php

include dirname(__DIR__) . '/tools/ClassLoader.php';
spl_autoload_register('ClassLoader::autoload');

use app\routes\Route;
Route::init();

use app\tools\Session;
Session::init();

use app\database\interfaces\Database;
Database::init();