<?php

use app\tools\Session;
use app\controllers\ViewCtrl;

ViewCtrl::includePageHeader(array('pageTitle' => "order_generate", 'assetsDir' => "assets/", 'login' => true));
$assetsDir = 'assets/';
if (!isset($trainIdList)) {
    $trainIdList = array(0);
}
if (!isset($trainNameList)) {
    $trainNameList = array("");
}
if (!isset($dateList)) {
    $dateList = array("");
}
if (!isset($start_stationList)) {
    $start_stationList = array("");
}
if (!isset($startStationIdList)) {
    $startStationIdList = array(0);
}
if (!isset($end_stationList)) {
    $end_stationList = array("");
}
if (!isset($endStationIdList)) {
    $endStationIdList = array(0);
}
if (!isset($remain_ticketsList)) {
    $remain_ticketsList = array("");
}
if (!isset($seatTypeList)) {
    $seatTypeList = array(0);
}
if (!isset($userName)) {
    $userName = array("");
}
if (!isset($userRealName)) {
    $userRealName = array("");
}
if (!isset($userTelNum)) {
    $userTelNum = array("");
}
?>

    <!-- File js link -->
    <script src="<?= $assetsDir ?>js/userOrderGenerate.js"></script>

    <div class="d-flex align-items-center justify-content-center position-absolute start-0 end-0"
         style="top: 75px; bottom: 100px">
        <div class="apple-block p-3 d-flex justify-content-center align-items-center"
             style="border-radius: 1rem; width: 60%; height: 80%">
            <div class="row w-100 h-100 justify-content-center align-items-center">
                <div class="row w-100  p-1 flex-column justify-content-around align-items-center" style="height: 40%">
                    <div class="row w-100 justify-content-center align-items-center" style="height: 50px">
                        <p class="text-center fs-2">
                            订单生成
                        </p>
                    </div>
                    <div class="row w-100 justify-content-center align-items-center" style="height: 150px">
                        <?php
                        for ($i = 0; $i < count($trainNameList); $i++) {
                            echo <<<END
                            <div class="col h-100 justify-content-starts ps-5" style="width: 250px;">
                                <div class="row w-100 ps-5">
                                    <p>车次：$trainNameList[$i]</p>
                                </div>
                                <div class="row w-100 ps-5">
                                    <p>日期：$dateList[$i]</p>
                                </div>
                                <div class="row w-100 ps-5">
                                    <p>始发站: $start_stationList[$i]&nbsp; ~ &nbsp; 终点站：$end_stationList[$i]</p>
                                </div>
                                <div class="row w-100 ps-5">
                                    <p>查询时余票：$remain_ticketsList[$i]</p>
                                </div>
                            </div>
                            END;
                        }
                        ?>
                        <!--                    <div class="col w-50 h-100 flex-column justify-content-center align-items-center">-->
                        <!--                        <div class="row w-100 pe-5">-->
                        <!--                            <p></p>-->
                        <!--                        </div>-->
                        <!--                        <div class="row w-100 ps-5">-->
                        <!--                            <p>用户名：< ?//= $userNameList?></p>-->
                        <!--                        </div>-->
                        <!--                        <div class="row w-100 ps-5">-->
                        <!--                            <p>真实姓名：< ?//= $userRealNameList?></p>-->
                        <!--                        </div>-->
                        <!--                        <div class="row w-100 ps-5">-->
                        <!--                            <p>电话号码：< ?//= $remain_ticketsList ?></p>-->
                        <!--                        </div>-->
                        <!--                    </div>-->
                    </div>
                </div>
                <div class="row w-100 p-1 d-flex flex-column justify-content-start align-items-center"
                     style="height: 60%">
                    <div class="table-title" style="height: 10%">
                        <div class="row">
                            <div class="col-sm-6 d-flex flex-column justify-content-center align-items-start">
                                <p class="fw-bold fs-3 text-start">Order Sheets</p>
                            </div>
                            <div class="col-sm-3 d-flex flex-column justify-content-center align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="includeMyselfCheckBox"
                                           checked>
                                    <label class="form-check-label" for="includeMyselfCheckBox">
                                        Include Myself.
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-3 d-flex flex-column justify-content-center align-items-center">
                                <button type="button" class="btn btn-primary add-new"><i class="bi bi-plus"></i> Add New
                                </button>
                            </div>
                        </div>
                    </div>
                    <form class="row w-100" style="overflow: scroll; height: 75%;" id="orderUsersForm"
                          action="preorderTrain" method="post">
                        <?php
                        for ($i = 0; $i < count($trainNameList); $i++) {
                            echo <<<END
                        <input type="hidden" name="trainId-{$i}" value="$trainIdList[$i]">
                        <input type="hidden" name="trainName-{$i}" value="$trainNameList[$i]">
                        <input type="hidden" name="date-{$i}" value="$dateList[$i]">
                        <input type="hidden" name="stationFromId-{$i}" value="$startStationIdList[$i]">
                        <input type="hidden" name="stationFrom-{$i}" value="$start_stationList[$i]">
                        <input type="hidden" name="stationToId-{$i}" value="$endStationIdList[$i]">
                        <input type="hidden" name="stationTo-{$i}" value="$end_stationList[$i]">
                        <input type="hidden" name="seatType-{$i}" value="$seatTypeList[$i]">
END;
                        }
                        ?>
                        <table class="table table-bordered border-secondary">
                            <thead>
                            <tr>
                                <th>UserName</th>
                                <th>UserRealName</th>
                                <th>UserTelNum</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="align-top p-3" style="height: 50px"><?= $userName ?><input type="hidden"
                                                                                                      name="userName0"
                                                                                                      value="<?= $userName ?>">
                                </td>
                                <td class="align-top p-3" style="height: 50px"><?= $userRealName ?><input type="hidden"
                                                                                                          name="userRealName0"
                                                                                                          value="<?= $userRealName ?>">
                                </td>
                                <td class="align-top p-3" style="height: 50px"><?= $userTelNum ?><input type="hidden"
                                                                                                        name="userTelNum0"
                                                                                                        value="<?= $userTelNum ?>">
                                </td>
                                <td class="align-top p-3" style="height: 50px">
                                    <a class="delete" title="Delete" data-toggle="tooltip"><i
                                                class="bi bi-trash fw-bold fs-5"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <?php
                        $count = count($trainNameList);
                        echo <<<END
                        <input type="hidden" name="count" id="count" value="$count"">
END;
                        ?>
                    </form>
                    <div class="row w-100 d-flex flex-row justify-content-center align-items-center"
                         style="height: 10%">
                        <button form="orderUsersForm" type="submit" formmethod="post" class="btn btn-primary w-25">
                            Submit
                        </button>
                    </div>
                    <div></div>
                </div>
            </div>
        </div>
    </div>

<?php
ViewCtrl::includePageFooter();
?>