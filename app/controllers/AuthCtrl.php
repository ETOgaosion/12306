<?php
namespace  app\controllers;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

use app\controllers\ViewCtrl;
use app\routes\Route;
use app\tools\Session;
use JetBrains\PhpStorm\NoReturn;
use app\models\Auth;
use app\controllers\AdminCtrl;
use app\config\CookieConfig;

define('NO_ERROR', 1);
define('ERROR_DUPLICATE_UNAME',2);
define('ERROR_DUPLICATE_U_TEL_NUM',3);
define('ERROR_DUPLICATE_AID',4);
define('ERROR_NOT_FOUND_UNAME',5);
define('ERROR_NOT_CORRECT_PASSWORD',6);
define('ERROR_NOT_CORRECT_AUTH',7);
define('FAIL_REASON_ARRAY', array(
    'ERROR_DUPLICATE_UNAME' => ERROR_DUPLICATE_UNAME,
    'ERROR_DUPLICATE_U_TEL_NUM' => ERROR_DUPLICATE_U_TEL_NUM,
    'ERROR_DUPLICATE_AID' => ERROR_DUPLICATE_AID,
    'ERROR_NOT_FOUND_UNAME' => ERROR_NOT_FOUND_UNAME,
    'ERROR_NOT_CORRECT_PASSWORD' => ERROR_NOT_CORRECT_PASSWORD,
    'ERROR_NOT_CORRECT_AUTH' => ERROR_NOT_CORRECT_AUTH));

class AuthCtrl
{
    public static function login(): void
    {
        $loginUserName = $_POST['loginUserNameInput'] ?? '';
        $loginPassword = $_POST['loginPasswordInput'] ?? '';
        $loginAuth = $_POST['loginAuthInput'] ?? '';
        $loginAsAdmin = $_POST['admin-login'] ?? '';
        if ($loginAsAdmin != 'Yes') {
            $resArray = Auth::userLogin($loginUserName, $loginPassword);
            Session::set('isAdmin', false);
            setcookie('isAdmin', false, CookieConfig::$expire_time, CookieConfig::$cookie_avail_path);
            if ($resArray['error'] == "NO_ERROR") {
                Session::set('loginSucceed', true);
                Session::set('loginFailed', false);
                Session::set('userName', $loginUserName);
                Session::set('uid', $resArray['uid']);
                setcookie('userName', $loginUserName, CookieConfig::$expire_time, CookieConfig::$cookie_avail_path);
                setcookie('uid', $resArray['uid'], CookieConfig::$expire_time, CookieConfig::$cookie_avail_path);
                Session::set('loggedIn', true);
                setcookie('loggedIn', true, CookieConfig::$expire_time, CookieConfig::$cookie_avail_path);
                Route::init();
                ViewCtrl::includeMain();
                die();
            }
            else {
                Session::set('loginSucceed', false);
                Session::set('loginFailed', true);
                Session::set('loginFailReason', $resArray['error']);
                ViewCtrl::includeIndex();
            }
        }
        else {
            $resArray = Auth::adminLogin($loginUserName, $loginPassword, $loginAuth);
            if ($resArray['error'] == "NO_ERROR") {
                Session::set('loginSucceed', true);
                Session::set('loginFailed', false);
                Session::set('userName', $loginUserName);
                Session::set('uid', $resArray['aid']);
                setcookie('userName', $loginUserName, CookieConfig::$expire_time, CookieConfig::$cookie_avail_path);
                setcookie('uid', $resArray['aid'], CookieConfig::$expire_time, CookieConfig::$cookie_avail_path);
                Session::set('loggedIn', true);
                setcookie('loggedIn', true, CookieConfig::$expire_time, CookieConfig::$cookie_avail_path);
                Session::set('isAdmin', true);
                setcookie('isAdmin', true);
                Route::init();
                AdminCtrl::adminQueryAll();
                die();
            }
            else {
                Session::set('loginSucceed', false);
                Session::set('loginFailed', true);
                Session::set('loginFailReason', $resArray['error']);
            }
        }
    }

    public static function register(): void
    {
        $registerUserName = $_POST['registerUserNameInput'] ?? '';
        $registerPassword = $_POST['registerPasswordInput'] ?? '';
        $registerEmail = $_POST['registerEmailInput'] ?? '';
        $registerTelNumInput = $_POST['registerTelNumInput'] ?? '';
        $registerRealNameInput = $_POST['registerRealNameInput'] ?? '';
        $registerAuth = $_POST['registerAuthInput'] ?? '';
        $registerAsAdmin = $_POST['admin-register'] ?? '';
        if ($registerAsAdmin != 'Yes') {
            $resArray = Auth::userRegister($registerUserName, $registerPassword, $registerRealNameInput, $registerTelNumInput, $registerEmail);
            Session::set('isAdmin', false);
            if ($resArray['error'] == "NO_ERROR") {
                Session::set('registerSucceed', true);
                Session::set('registerFailed', false);
                ViewCtrl::includeIndex();
            }
            else {
                Session::set('registerSucceed', false);
                Session::set('registerFailed', true);
                Session::set('registerFailReason', $resArray['error']);
                ViewCtrl::includeIndex();
            }
        }
        else {
            $resArray = Auth::adminRegister($registerUserName, $registerPassword, $registerTelNumInput, $registerEmail, $registerAuth);
            Session::set('isAdmin', true);
            if ($resArray['error'] == "NO_ERROR") {
                Session::unsetAll();
                Session::set('registerSucceed', true);
                Session::set('registerFailed', false);
                ViewCtrl::includeIndex();
            }
            else {
                Session::set('registerSucceed', false);
                Session::set('registerFailed', true);
                Session::set('registerFailReason', $resArray['error']);
                ViewCtrl::includeIndex();
            }
        }
    }

    #[NoReturn] public static function logout(): void
    {
        Session::unsetAll();
        Route::init();
        ViewCtrl::includeIndex();
        die();
    }
}