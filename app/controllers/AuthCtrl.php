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
            $_SESSION['isAdmin'] = false;
            setcookie('isAdmin', false, CookieConfig::$expire_time, CookieConfig::$cookie_avail_path);
            if ($resArray['error'] == "NO_ERROR") {
                $_SESSION['loginSucceed'] = true;
                $_SESSION['loginFailed'] = false;
                $_SESSION['userName'] = $loginUserName;
                $_SESSION['uid'] = $resArray['uid'];
                setcookie('userName', $loginUserName, CookieConfig::$expire_time, CookieConfig::$cookie_avail_path);
                setcookie('uid', $resArray['uid'], CookieConfig::$expire_time, CookieConfig::$cookie_avail_path);
                $_SESSION['loggedIn'] = true;
                setcookie('loggedIn', true, CookieConfig::$expire_time, CookieConfig::$cookie_avail_path);
                Route::init();
                ViewCtrl::includeMain();
                die();
            }
            else {
                $_SESSION['loginSucceed'] = false;
                $_SESSION['loginFailed'] = true;
                $_SESSION['loginFailReason'] = $resArray['error'];
                ViewCtrl::includeIndex();
            }
        }
        else {
            $resArray = Auth::adminLogin($loginUserName, $loginPassword, $loginAuth);
            if ($resArray['error'] == "NO_ERROR") {
                $_SESSION['loginSucceed'] = true;
                $_SESSION['loginFailed'] = false;
                $_SESSION['userName'] = $loginUserName;
                $_SESSION['uid'] = $resArray['aid'];
                setcookie('userName', $loginUserName, CookieConfig::$expire_time, CookieConfig::$cookie_avail_path);
                setcookie('uid', $resArray['aid'], CookieConfig::$expire_time, CookieConfig::$cookie_avail_path);
                $_SESSION['loggedIn'] = true;
                setcookie('loggedIn', true, CookieConfig::$expire_time, CookieConfig::$cookie_avail_path);
                $_SESSION['isAdmin'] = true;
                setcookie('isAdmin', true);
                Route::init();
                ViewCtrl::includeMain();
                die();
            }
            else {
                $_SESSION['loginSucceed'] = false;
                $_SESSION['loginFailed'] = true;
                $_SESSION['loginFailReason'] = $resArray['error'];
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
            $_SESSION['isAdmin'] = false;
            if ($resArray['error'] == "NO_ERROR") {
                $_SESSION['registerSucceed'] = true;
                $_SESSION['registerFailed'] = false;
                ViewCtrl::includeIndex();
            }
            else {
                $_SESSION['registerSucceed'] = false;
                $_SESSION['registerFailed'] = true;
                $_SESSION['registerFailReason'] = $resArray['error'];
                ViewCtrl::includeIndex();
            }
        }
        else {
            $resArray = Auth::adminRegister($registerUserName, $registerPassword, $registerTelNumInput, $registerEmail, $registerAuth);
            $_SESSION['isAdmin'] = true;
            if ($resArray['error'] == "NO_ERROR") {
                $_SESSION['registerSucceed'] = true;
                $_SESSION['registerFailed'] = false;
            }
            else {
                $_SESSION['registerSucceed'] = false;
                $_SESSION['registerFailed'] = true;
                $_SESSION['registerFailReason'] = $resArray['error'];
                ViewCtrl::includeIndex();
            }
        }
    }

    #[NoReturn] public static function logout(): void
    {
        Session::unset('userName');
        Session::unset('loggedIn');
        Session::unset('loginSucceed');
        Route::init();
        ViewCtrl::includeIndex();
        die();
    }
}