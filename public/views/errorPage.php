<?php

use app\tools\Session;
use app\controllers\ViewCtrl;

ViewCtrl::includePageHeader(array('pageTitle' => "error_Page", 'assetsDir' => "assets/", 'login' => true));
$assetsDir = 'assets/';
?>

    <div class="d-flex align-items-center justify-content-center position-absolute start-0 end-0"
         style="top: 75px; bottom: 100px">
        <div class="h-25 w-50 d-flex flex-column align-items-center justify-content-center bg-light p-5"
             style="--bs-bg-opacity: 0.8">
            <div class="row w-50 h-50 d-flex flex-row justify-content-center align-items-center">
                <p class="fs-1 text-center">404 NOT FOUND~</p>
            </div>
            <div class="row w-100 h-50 d-flex flex-row justify-content-center align-items-center">
                <div class="h-100 w-25 d-flex flex-row justify-content-center align-items-center">
                    <a type="button" class="btn btn-primary" href="index"> return to Home Page</a>
                </div>
            </div>
        </div>
    </div>

<?php
ViewCtrl::includePageFooter();
?>