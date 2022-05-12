<?php

use app\tools\Session;
use app\controllers\ViewCtrl;

ViewCtrl::includePageHeader(array('pageTitle' => "user_Space", 'assetsDir' => "assets/", 'login' => true));
$assetsDir = 'assets/';
if (!isset($userName)) {
    $userName = "Blue Space";
}
if (!isset($userEmail)) {
    $userEmail = "gaoziyuan19@mails.ucas.ac.cn";
}
if (!isset($userRealName)) {
    $userRealName = "Gao Ziyuan";
}
if (!isset($userTelNum)) {
    $userTelNum = "19801190365";
}
if (!array_key_exists('userQueryResArray', $_SESSION)) {
    $orderIDList = array();
    $dateList = array();
    $startStationList = array();
    $arriveStationList = array();
    $priceList = array();
    $orderStatusList = array();
} else {
    $resArray = Session::get('userQueryResArray');
    $orderIDList = array_column($resArray, 'order_id');
    $dateList = array_column($resArray, 'date');
    $startStationList = array_column($resArray, 'station_leave');
    $arriveStationList = array_column($resArray, 'station_arrive');
    $priceList = array_column($resArray, 'price');
    $orderStatusList = array_column($resArray, 'status');
}
?>

    <div class="d-flex align-items-center justify-content-center position-absolute start-0 end-0"
         style="top: 75px; bottom: 100px">
        <div class="h-100 w-75 d-flex flex-column align-items-center justify-content-start bg-light pt-3 ps-3 pe-3 pb-0"
             style="--bs-bg-opacity: 0.8">
            <div class="row w-100 d-flex flex-row align-items-center justify-content-center" style="height: 50px">
                <p class="fw-bold fs-2 text-center">用户空间</p>
            </div>
            <div class="row h-25 w-100 p-5 justify-content-center">
                <div class="nav nav-tabs border-dark d-flex flex-row align-items-center justify-content-center">
                    <p>Settings</p>
                </div>
                <div class="row w-100 h-50 d-flex flex-row justify-content-center align-items-center">
                    <div class="col-6 d-flex flex-row justify-content-start align-items-center" style="height: 50px">
                        <div class="col-4 h-100 d-flex justify-content-start align-items-center">
                            <p class="d-flex flex-row align-item-center">UserName:</p>
                        </div>
                        <div class="col-7 h-100 d-flex justify-content-start align-items-center">
                            <p class="d-flex flex-row align-item-center"><?= $userName ?></p>
                        </div>
                        <div class="col-1 h-100 d-flex justify-content-center align-items-center">
                            <a href="#" id="editUserName"><i class="bi bi-pencil-square fs-5 fw-bold"></i></a>
                        </div>
                    </div>
                    <div class="col-6 d-flex flex-row justify-content-start align-items-center" style="height: 50px">
                        <div class="col-4 h-100 d-flex justify-content-start align-items-center">
                            <p class="d-flex flex-row align-item-center">Password:</p>
                        </div>
                        <div class="col-1 h-100 d-flex justify-content-center align-items-center">
                            <a href="#" id="editPassword"><i class="bi bi-pencil-square fs-5 fw-bold"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row w-100 h-50 d-flex flex-row justify-content-center align-items-center">
                    <div class="col-6 d-flex flex-row justify-content-start align-items-center" style="height: 50px">
                        <div class="col-4 h-100 d-flex justify-content-start align-items-center">
                            <p class="d-flex flex-row align-item-center">UserRealName:</p>
                        </div>
                        <div class="col-7 h-100 d-flex justify-content-start align-items-center">
                            <p class="d-flex flex-row align-item-center"><?= $userRealName ?></p>
                        </div>
                        <div class="col-1 h-100 d-flex justify-content-center align-items-center">
                            <a href="#" id="editUserRealName"><i class="bi bi-pencil-square fs-5 fw-bold"></i></a>
                        </div>
                    </div>
                    <div class="col-6 d-flex flex-row justify-content-start align-items-center" style="height: 50px">
                        <div class="col-4 h-100 d-flex justify-content-start align-items-center">
                            <p class="d-flex flex-row align-item-center">UserTelNum:</p>
                        </div>
                        <div class="col-7 h-100 d-flex justify-content-start align-items-center">
                            <p class="d-flex flex-row align-item-center"><?= $userTelNum ?></p>
                        </div>
                        <div class="col-1 h-100 d-flex justify-content-center align-items-center">
                            <a href="#" id="editUserTelNum"><i class="bi bi-pencil-square fs-5 fw-bold"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row w-100 p-5 flex-column justify-content-start position-relative" style="height: 65%">
                <div class="nav nav-tabs border-dark d-flex flex-row align-items-center justify-content-center">
                    <p class="d-flex flex-row align-item-center">Orders Query</p>
                </div>
                <form class="row w-100 pt-2" style="height: 100px" action="userQueryOrder" method="post">
                    <div class="col d-flex flex-row justify-content-center align-items-center" style="width: 40%">
                        <div class="col-5 d-flex flex-row justify-content-center align-items-center">
                            <label for="inputStartQueryDate" class="col-form-label">Start Query Date:</label>
                        </div>
                        <div class="col-7">
                            <input type="date" name="inputStartQueryDate" id="inputStartQueryDate" class="form-control">
                        </div>
                    </div>
                    <div class="col d-flex flex-row justify-content-center align-items-center" style="width: 40%">
                        <div class="col-5 d-flex flex-row justify-content-center align-items-center">
                            <label for="inputEndQueryDate" class="col-form-label">End Query Date:</label>
                        </div>
                        <div class="col-7">
                            <input type="date" name="inputEndQueryDate" id="inputEndQueryDate" class="form-control">
                        </div>
                    </div>
                    <div class="col d-flex flex-row justify-content-center align-items-center" style="width: 20%">
                        <div class="col-5 d-flex flex-row justify-content-center align-items-center">
                            <button type="submit" formmethod="post" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
                <div class="row position-absolute" style="overflow: scroll; top: 200px; bottom: 0; right: 0; left: 0">
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
                            if ($orderStatusList[$i] == 2) {
                                echo <<<END
                            <a href="cancelOrder?oid={$orderIDList[$i]}" id="cancelOrder"><i class="bi bi-trash fw-bold fs-5"></i></a>
                            END;
                            }
                            echo "</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php
ViewCtrl::includePageFooter();
?>