<?php

use app\tools\Session;
use app\controllers\ViewCtrl;

ViewCtrl::includePageHeader(array('pageTitle' => "admin_Main", 'assetsDir' => "assets/", 'login' => true));
$assetsDir = 'assets/';
if (!isset($totalOrder)) {
    $totalOrder = 0;
}
if (!isset($totalPrice)) {
    $totalPrice = 0;
}
if (!isset($totalOrder)) {
    $totalOrder = 0;
}
if (!isset($hotTrains)) {
    $hotTrains = "";
    $hotTrainsArray = array();
} else {
    $hotTrainsArray = explode(',', substr($hotTrains, 1, strlen($hotTrains) - 2));
}
if (!isset($registerUserIdList)) {
    $registerUserIdList = array();
}
if (!isset($registerUserNameList)) {
    $registerUserNameList = array();
}
if (!array_key_exists('adminQueryUserInfoResArray', $_SESSION)) {
    $userName = "";
    $userRealName = "";
    $userEmail = "";
    $userTelNumber = "";
} else {
    $infoResArray = Session::get('adminQueryUserInfoResArray');
    $userName = $infoResArray['user_name'];
    $userRealName = $infoResArray['user_real_name'];
    $userTelNumber = $infoResArray['user_telnum'];
    $userEmail = $infoResArray['user_email'];
}
if (!array_key_exists('adminQueryUserOrdersResArray', $_SESSION)) {
    $orderIDList = array();
    $dateList = "";
    $startStationList = array();
    $arriveStationList = array();
    $priceList = array();
    $orderStatusList = array();
} else {
    $orderResArray = Session::get('adminQueryUserOrdersResArray');
    $orderIDList = array_column($orderResArray, 'order_id');
    $dateList = array_column($orderResArray, 'date');
    $startStationList = array_column($orderResArray, 'station_leave');
    $arriveStationList = array_column($orderResArray, 'station_arrive');
    $priceList = array_column($orderResArray, 'price');
    $orderStatusList = array_column($orderResArray, 'status');
}
?>

    <div class="d-flex align-items-center justify-content-center position-absolute start-0 end-0"
         style="top: 75px; bottom: 100px">
        <div class="h-100 d-flex flex-column align-items-center justify-content-start apple-block position-relative"
             style="--bs-bg-opacity: 0.8; width: 90%;">
            <div class="row w-100 d-flex flex-row align-items-center justify-content-center p-3" style="height: 50px">
                <p class="fw-bold fs-2 text-center">管理员空间</p>
            </div>
            <div class="position-absolute bottom-0 start-0 end-0" style="top: 50px">
                <div class="row w-100 p-5 justify-content-center" style="height: 30%">
                    <div class="nav nav-tabs border-dark d-flex flex-row align-items-center justify-content-center">
                        <p>Order and Train Info</p>
                    </div>
                    <table class="table table-striped table-borderless" style="--bs-bg-opacity: 0">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 33%">Total Orders</th>
                            <th class="text-center" style="width: 33%">Total Price</th>
                            <th class="text-center" style="width: 33%">Hot Trains</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <form class="d-flex justify-content-center align-items-center" action="adminRefreshOrders" method="post">
                                    <button class="btn btn-success text-center"
                                            style="border-radius: 2rem; width: 100px; height: 100px">
                                        <?= $totalOrder ?>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <form class="d-flex justify-content-center align-items-center" action="adminRefreshOrders" method="post">
                                    <button class="btn btn-info text-center"
                                            style="border-radius: 2rem; width: 100px; height: 100px">
                                        <?= $totalPrice ?>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <form class="d-flex justify-content-center align-items-center" action="adminRefreshOrders" method="post">
                                    <button class="btn btn-danger text-dark text-center"
                                            style="border-radius: 2rem; width: 100%; height: 100px">
                                        <?php
                                        for ($i = 0; $i < 3 && $i * 3 < count($hotTrainsArray); $i++) {
                                            for ($j = 0; $j < 3 && $i * 3 + $j < count($hotTrainsArray); $j++) {
                                                echo $hotTrainsArray[$i * 3 + $j] . "&nbsp;";
                                            }
                                            echo "<br>";
                                        }
                                        if (count($hotTrainsArray) == 10) {
                                            echo $hotTrainsArray[9];
                                        }
                                        ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row w-100 p-5 justify-content-center" style="height: 70%; padding-top: 50px">
                    <div class="nav nav-tabs border-dark d-flex flex-row align-items-center justify-content-center">
                        <p>User Info</p>
                    </div>
                    <div class="col-5 h-100 d-flex flex-column justify-content-start align-items-center"
                         style="overflow: scroll">
                        <table class="table table-striped table-bordered border-secondary">
                            <thead>
                            <tr>
                                <th style="width: 50%">用户ID</th>
                                <th style="width: 50%">用户名</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            for ($i = 0; $i < count($registerUserIdList); $i++) {
                                echo <<<END
                            <tr>
                                <td><a href="adminQueryUserInfo?userId={$registerUserIdList[$i]}&userName={$registerUserNameList[$i]}">{$registerUserIdList[$i]}</a></td>
                                <td><a href="adminQueryUserInfo?userId={$registerUserIdList[$i]}&userName={$registerUserNameList[$i]}">{$registerUserNameList[$i]}</a></td>
                            </tr>
END;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-7 h-100 d-flex flex-column justify-content-start align-items-center position-relative">
                        <div class="row w-100" style="height: 30px;">
                            <div class="col h-100 w-50 p-3">UserName: <?= $userName ?></div>
                            <div class="col h-100 w-50 p-3">UserRealName: <?= $userRealName ?></div>
                        </div>
                        <div class="row w-100" style="height: 50px;">
                            <div class="col h-100 w-50 p-3">UserEmail: <?= $userEmail ?></div>
                            <div class="col h-100 w-50 p-3">UserTelNum: <?= $userTelNumber ?></div>
                        </div>
                        <form class="row w-50 text-center p-2" style="height: 50px;" action="adminRefreshUserOrder" method="post">
                            <input type="hidden" name="userName" value="<?= $userName ?>">
                            <button class="btn btn-primary" style="border-radius: 1rem">
                                User Orders
                            </button>
                        </form>
                        <div class="row w-100 position-absolute bottom-0 p-2" style="top:150px; overflow: scroll">
                            <table class="table table-striped table-bordered border-secondary">
                                <thead>
                                <tr>
                                    <th scope="col" class="text-center">订单号</th>
                                    <th scope="col" class="text-center">日期</th>
                                    <th scope="col" class="text-center">出发站</th>
                                    <th scope="col" class="text-center">到达站</th>
                                    <th scope="col" class="text-center">总票价</th>
                                    <th scope="col" class="text-center">订单状态</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                for ($i = 0; $i < count($orderIDList); $i++) {
                                    echo <<<END
                        <tr>
                        <td class="align-top p-3" style="height: 50px">$orderIDList[$i]</td>
                        <td class="align-top p-3" style="height: 50px">$dateList[$i]</td>
                        <td class="align-top p-3" style="height: 50px">$startStationList[$i]</td>
                        <td class="align-top p-3" style="height: 50px">$arriveStationList[$i]</td>
                        <td class="align-top p-3" style="height: 50px">$priceList[$i]</td>
                        <td class="align-top p-3" style="height: 50px">$orderStatusList[$i]
                        END;
                                    echo "</td></tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
ViewCtrl::includePageFooter();
?>