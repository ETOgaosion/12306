<?php
use app\controllers\ViewCtrl;
ViewCtrl::includePageHeader(array('pageTitle' => "order_confirm", 'assetsDir' => "assets/", 'login' => true));
$assetsDir = 'assets/';
if (!isset($trainId)) {
    $trainId = 0;
}
if (!isset($trainName)) {
    $trainName = "";
}
if (!isset($date)) {
    $date = date('Y-m-d', time());
}
if (!isset($start_station)) {
    $start_station = "";
}
if (!isset($startStationId)) {
    $startStationId = 0;
}
if (!isset($end_station)) {
    $end_station = "";
}
if (!isset($endStationId)) {
    $endStationId = 0;
}
if (!isset($remain_tickets)) {
    $remain_tickets = "";
}
if (!isset($seatType)) {
    $seatType = 0;
}
if (!isset($userNameList)) {
    $userNameList = array();
}
if (!isset($userRealNameList)) {
    $userRealNameList = array();
}
if (!isset($userTelNumList)) {
    $userTelNumList = array();
}
if (!isset($seatNum)) {
    $seatNum = 0;
}
if (!isset($orderId)) {
    $orderId = 0;
}
?>

<!-- File js link -->
<script src="<?= $assetsDir?>js/userOrderGenerate.js"></script>

<div class="d-flex align-items-center justify-content-center position-absolute start-0 end-0"
     style="top: 75px; bottom: 100px">
    <div class="bg-light p-3 d-flex justify-content-center align-items-center" style="border-radius: 1rem; width: 60%; height: 80%">
        <div class="row w-100 h-100 justify-content-center align-items-center">
            <div class="row w-100  p-1 flex-column justify-content-around align-items-center" style="height: 40%">
                <div class="row w-100 justify-content-center align-items-center" style="height: 50px">
                    <p class="text-center fs-2">
                        订单生成
                    </p>
                </div>
                <div class="row w-100 h-auto justify-content-center align-items-center">
                    <div class="col-6 h-100 justify-content-starts ps-5">
                        <div class="row w-100 ps-5">
                            <p>车次：<?= $trainName?></p>
                        </div>
                        <div class="row w-100 ps-5">
                            <p>日期：<?= $date?></p>
                        </div>
                        <div class="row w-100 ps-5">
                            <p>始发站: <?= $start_station ?> &nbsp; ~ &nbsp; 终点站：<?= $end_station ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row w-100 p-1 d-flex flex-column justify-content-start align-items-center" style="height: 60%">
                <div class="table-title" style="height: 10%">
                    <div class="row">
                        <div class="col-sm-12 d-flex flex-column justify-content-center align-items-start">
                            <p class="fw-bold fs-3 text-start">Order Sheets</p>
                        </div>
                    </div>
                </div>
                <div class="row w-100" style="overflow: scroll; height: 75%;">
                    <table class="table table-bordered border-secondary">
                        <thead>
                        <tr>
                            <th style="width: 25%">UserName</th>
                            <th style="width: 25%">UserRealName</th>
                            <th style="width: 25%">UserTelNum</th>
                            <th style="width: 25%">SeatNum</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            for($i = 0; $i < count($userNameList); $i++) {
                                $seatNumber = $seatNum + $i;
                                echo <<<END
                            <tr>
                            <td class="align-top p-3" style="height: 50px">{$userNameList[$i]}</td>
                            <td class="align-top p-3" style="height: 50px">{$userRealNameList[$i]}</td>
                            <td class="align-top p-3" style="height: 50px">{$userTelNumList[$i]}</td>
                            <td class="align-top p-3" style="height: 50px">{$seatNumber}</td>
                            </tr>
                            END;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <form class="row w-100 d-flex flex-row justify-content-around align-items-center" style="height: 10%" action="orderTrain" method="post">
                    <input type="hidden" name="orderId" value="<?= $orderId?>">
                    <input type="hidden" name="uidNum" value="<?= count($userTelNumList)?>">
                    <button type="submit" formmethod="post" class="btn btn-success w-25" id="confirmBtn">
                        Confirm
                    </button>
                    <a href="index" class="btn btn-danger w-25" id="cancelBtn">
                        Cancel
                    </a>
                </form>
                <div></div>
            </div>
        </div>
    </div>
</div>

<?php
ViewCtrl::includePageFooter();
?>