<?php
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
    $hotTrains = array();
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
}
else {
    $infoResArray = $_SESSION['adminQueryUserInfoResArray'];
    $userName = $infoResArray['user_name'];
    $userRealName = $infoResArray['user_real_name'];
    $userTelNumber = $infoResArray['user_telnum'];
    $userEmail = $infoResArray['user_email'];
}
if(!array_key_exists('adminQueryUserOrdersResArray', $_SESSION)) {
    $orderIDList = array();
    $dateList = "";
    $startStationList = array();
    $arriveStationList = array();
    $priceList = array();
    $orderStatusList = array();
}
else {
    $orderResArray = $_SESSION['adminQueryUserOrdersResArray'];
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
    <div class="h-100 w-75 d-flex flex-column align-items-center justify-content-start bg-light position-relative"
         style="--bs-bg-opacity: 0.8">
        <div class="row w-100 d-flex flex-row align-items-center justify-content-center p-3" style="height: 50px">
            <p class="fw-bold fs-2 text-center">管理员空间</p>
        </div>
        <div class="position-absolute bottom-0 start-0 end-0" style="top: 50px">
            <div class="row w-100 p-5 justify-content-center" style="height: 35%">
                <div class="nav nav-tabs border-dark d-flex flex-row align-items-center justify-content-center">
                    <p>Order and Train Info</p>
                </div>
                <table class="table table-light table-striped table-borderless" style="--bs-bg-opacity: 0">
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
                            <div class="d-flex justify-content-center align-items-center">
                                <button class="btn btn-success text-center"
                                        style="border-radius: 2rem; width: 200px; height: 200px">
                                    <?= $totalOrder ?>
                                </button>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                <button class="btn btn-info text-center"
                                        style="border-radius: 2rem; width: 200px; height: 200px">
                                    <?= $totalPrice ?>
                                </button>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                <button class="btn btn-danger text-dark text-center"
                                        style="border-radius: 2rem; width: 200px; height: 200px">
                                    <?php
                                    for ($i = 0; $i < 5; $i++) {
                                        echo $hotTrains[$i * 2] . "&nbsp;" . $hotTrains[$i * 2 + 1] . "<br>";
                                    }
                                    ?>
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="row w-100 p-5 justify-content-center" style="height: 65%">
                <div class="nav nav-tabs border-dark d-flex flex-row align-items-center justify-content-center">
                    <p>User Info</p>
                </div>
                <div class="col-5 h-100 d-flex flex-column justify-content-start align-items-center"
                     style="overflow: scroll">
                </div>
                <form class="col-1 h-100 d-flex flex-column justify-content-center align-items-center" action="adminQueryUserInfo" method="post">
                    <label for="queryUserNameInput" class="form-label">User Name:</label>
                    <input type="text" name="queryUserNameInput" id="queryUserNameInput" class="form-control">
                    <button class="btn btn-primary" type="submit" formmethod="post">Search Info</button>
                </form>
                <div class="col-6 h-100 d-flex flex-column justify-content-start align-items-center position-relative">
                    <div class="row w-100" style="height: 50px;">
                        <div class="col h-100 w-50 p-3">UserName: <?= $userName?></div>
                        <div class="col h-100 w-50 p-3">UserRealName: <?= $userRealName?></div>
                    </div>
                    <div class="row w-100" style="height: 50px;">
                        <div class="col h-100 w-50 p-3">UserEmail: <?= $userEmail?></div>
                        <div class="col h-100 w-50 p-3">UserTelNum: <?php
                            foreach ($userTelNumber as $num) {
                                echo $num;
                            }
                            ?></div>
                    </div>
                    <div class="row w-25 text-center p-2" style="height: 50px;">
                        <button class="btn btn-primary" style="border-radius: 1rem">
                            User Orders
                        </button>
                    </div>
                    <div class="row w-100 position-absolute bottom-0 p-2" style="top:150px; overflow: scroll">
                        <table class="table-light table-striped table-bordered border-secondary">
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