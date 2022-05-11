<?php
use app\controllers\ViewCtrl;
ViewCtrl::includePageHeader(array('pageTitle' => "order_confirm", 'assetsDir' => "assets/", 'login' => true));
$assetsDir = 'assets/';
$trainName = "1055";
$date = date('Y-m-d', time());
$start_station = "北京南";
$end_station = "郑州东";
$remain_tickets = 5;
$userNameList = array("007");
$userRealNameList = array("Blue Space");
$userTelNumList = "19801190365";
?>

<!-- File js link -->
<script src="<?= $assetsDir?>js/userOrderGenerate.js"></script>

<div class="d-flex align-items-center justify-content-center position-absolute start-0 end-0"
     style="top: 75px; bottom: 100px">
    <div class="bg-light p-3 d-flex justify-content-center align-items-center" style="border-radius: 1rem; width: 40%; height: 60%">
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
                        <div class="row w-100 ps-5">
                            <p>查询时余票：<?= $remain_tickets ?></p>
                        </div>
                    </div>
                    <!--                    <div class="col w-50 h-100 flex-column justify-content-center align-items-center">-->
                    <!--                        <div class="row w-100 pe-5">-->
                    <!--                            <p></p>-->
                    <!--                        </div>-->
                    <!--                        <div class="row w-100 ps-5">-->
                    <!--                            <p>用户名：< ?//= $userName?></p>-->
                    <!--                        </div>-->
                    <!--                        <div class="row w-100 ps-5">-->
                    <!--                            <p>真实姓名：< ?//= $userRealName?></p>-->
                    <!--                        </div>-->
                    <!--                        <div class="row w-100 ps-5">-->
                    <!--                            <p>电话号码：< ?//= $remain_tickets ?></p>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
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
                            <th style="width: 33%">UserName</th>
                            <th style="width: 33%">UserRealName</th>
                            <th style="width: 33%">UserTelNum</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            for($i = 0; $i < count($userNameList); $i++) {
                                echo <<<END
                            <tr>
                            <td class="align-top p-3" style="height: 50px">{$userNameList[$i]}</td>
                            <td class="align-top p-3" style="height: 50px">{$userRealNameList[$i]}</td>
                            <td class="align-top p-3" style="height: 50px">$userTelNumList[$i]</td>
                            </tr>
                            END;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="row w-100 d-flex flex-row justify-content-around align-items-center" style="height: 10%">
                    <button type="submit" formmethod="post" class="btn btn-success w-25" id="confirmBtn">
                        Confirm
                    </button>
                    <button type="submit" formmethod="post" class="btn btn-danger w-25" id="cancelBtn">
                        Cancel
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