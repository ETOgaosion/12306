<?php
namespace app\controllers;

use JetBrains\PhpStorm\NoReturn;
use app\models\Auth;

define('NO_ERROR', 1);
define('ERROR_DUPLICATE_UNAME',2);
define('ERROR_DUPLICATE_U_TEL_NUM',3);
define('ERROR_DUPLICATE_AID',4);
define('ERROR_NOT_FOUND_UNAME',5);
define('ERROR_NOT_CORRECT_PASSWORD',6);
define('ERROR_NOT_CORRECT_AUTH',7);
define('FAIL_REASON_ARRAY', array('ERROR_DUPLICATE_UNAME', 'ERROR_DUPLICATE_U_TEL_NUM', 'ERROR_DUPLICATE_AID', 'ERROR_NOT_FOUND_UNAME', 'ERROR_NOT_CORRECT_PASSWORD', 'ERROR_NOT_CORRECT_AUTH'));

class AuthCtrl
{
    public static function login(): void
    {
        $loginUserName = $_POST['loginUserNameInput'] ?? '';
        $loginPassword = $_POST['loginPasswordInput'] ?? '';
        $loginAuth = $_POST['loginAuthInput'] ?? '';
        $loginAsAdmin = $_POST['admin-login'] ?? '';
        if ($loginAsAdmin != 'on') {
            $resArray = Auth::userLogin($loginUserName, $loginPassword);
            $_SESSION['isAdmin'] = false;
            $_COOKIE['isAdmin'] = false;
            if ($resArray['error'] == NO_ERROR) {
                $_SESSION['loginSucceed'] = true;
                $_SESSION['loginFailed'] = false;
                $_SESSION['userName'] = $loginUserName;
                $_SESSION['uid'] = $resArray['uid'];
                $_COOKIE['userName'] = $loginUserName;
                $_COOKIE['uid'] = $resArray['uid'];
            }
            else {
                $_SESSION['loginSucceed'] = false;
                $_SESSION['loginFailed'] = true;
                $_SESSION['loginFailReason'] = FAIL_REASON_ARRAY[$resArray['error'] - 2];
            }
        }
        else {
            $resArray = Auth::adminLogin($loginUserName, $loginPassword, $loginAuth);
            if ($resArray['error'] == NO_ERROR) {
                $_SESSION['loginSucceed'] = true;
                $_SESSION['loginFailed'] = false;
                $_SESSION['userName'] = $loginUserName;
                $_SESSION['uid'] = $resArray['uid'];
                $_COOKIE['userName'] = $loginUserName;
                $_COOKIE['uid'] = $resArray['uid'];
                $_SESSION['isAdmin'] = true;
                $_COOKIE['isAdmin'] = true;
            }
            else {
                $_SESSION['loginSucceed'] = false;
                $_SESSION['loginFailed'] = true;
                $_SESSION['loginFailReason'] = FAIL_REASON_ARRAY[$resArray['error'] - 2];
            }
        }
    }

    public static function register(): void
    {
        $registerUserName = $_POST['registerUserNameInput'] ?? '';
        $registerPassword = $_POST['registerPasswordInput'] ?? '';
        $registerEmail = $_POST['registerEmailInput'] ?? '';
        $registerTelNumInputRaw = $_POST['registerTelNumInput'] ?? '';
        $registerTelNumInput = array();
        for ($i = 0; $i < 11; $i++){
            $registerTelNumInput[$i] = $registerTelNumInputRaw[$i];
        }
        $registerRealNameInput = $_POST['registerRealNameInput'] ?? '';
        $registerAuth = $_POST['registerAuthInput'] ?? '';
        $registerAsAdmin = $_POST['admin-register'] ?? '';
        if ($registerAsAdmin != 'on') {
            $resArray = Auth::userRegister($registerUserName, $registerPassword, $registerRealNameInput, $registerTelNumInput, $registerEmail);
            $_SESSION['isAdmin'] = false;
            $_COOKIE['isAdmin'] = false;
            if ($resArray['error'] == NO_ERROR) {
                $_SESSION['registerSucceed'] = true;
                $_SESSION['registerFailed'] = false;
                $_SESSION['userName'] = $registerUserName;
                $_SESSION['uid'] = $resArray['uid'];
                $_COOKIE['userName'] = $registerUserName;
                $_COOKIE['uid'] = $resArray['uid'];
            }
            else {
                $_SESSION['registerSucceed'] = false;
                $_SESSION['registerFailed'] = true;
                $_SESSION['registerFailReason'] = FAIL_REASON_ARRAY[$resArray['error'] - 2];
            }
        }
        else {
            $resArray = Auth::adminRegister($registerUserName, $registerPassword, $registerTelNumInput, $registerEmail, $registerAuth);
            $_SESSION['isAdmin'] = true;
            if ($resArray['error'] == NO_ERROR) {
                $_SESSION['registerSucceed'] = true;
                $_SESSION['registerFailed'] = false;
                $_SESSION['userName'] = $registerUserName;
                $_SESSION['uid'] = $resArray['uid'];
                $_COOKIE['userName'] = $registerUserName;
                $_COOKIE['uid'] = $resArray['uid'];
                $_COOKIE['isAdmin'] = true;
            }
            else {
                $_SESSION['registerSucceed'] = false;
                $_SESSION['registerFailed'] = true;
                $_SESSION['registerFailReason'] = FAIL_REASON_ARRAY[$resArray['error'] - 2];
            }
        }
    }
}