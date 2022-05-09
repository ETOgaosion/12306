<?php

include dirname(__DIR__) . '/tools/ClassLoader.php';
spl_autoload_register('ClassLoader::autoload');

use routes\Route;
Route::init();