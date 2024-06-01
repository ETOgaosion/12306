<?php

use app\tools\Session;
use app\controllers\ViewCtrl;

ViewCtrl::includePageHeader(array('pageTitle' => "query_res", 'assetsDir' => "assets/", 'login' => true));
$assetsDir = 'assets/';
if (!isset($date)) {
    $date = date('Y-m-d', time());
}
if (!isset($trainId)) {
    $trainId = 0;
}
if (!isset($trainName)) {
    $trainName = "1055";
}
if (!isset($start_station)) {
    $start_station = "北京南";
}
if (!isset($end_station)) {
    $end_station = "郑州东";
}
if (!isset($queryRes)) {
    $queryRes = array();
}
?>

    <!-- free map api: OpenLayers -->
    <!-- Map css api link -->
<link href="https://cdn.jsdelivr.net/npm/ol@v9.2.4/ol.css"
      rel="stylesheet"
      type="text/css">

<!-- Map js api link -->
    <script src="https://cdn.jsdelivr.net/npm/ol@v9.2.4/dist/ol.js"
            type="text/javascript"></script>

<!-- File js link -->
    <script src="<?= $assetsDir ?>js/userQueryRes.js"></script>

<div class="d-flex align-items-center justify-content-center position-absolute start-0 end-0"
     style="top: 75px; bottom: 100px">
    <div class="h-100 w-75 d-flex flex-column align-items-center justify-content-start apple-block p-5"
         style="--bs-bg-opacity: 0.8">
        <div class="row flex-row justify-content-center align-items-center w-100" style="height: 50px;">
            <p class="fs-2 fw-bold text-center">车次查询信息</p>
        </div>
        <div class="row w-100" style="height: 25%">
            <div class="col w-50 h-100 p-3">
                <div class="row w-100" style="height: 200px;">
                    <div class="row flex-column justify-content-center align-items-center w-100 ps-5">
                        <div class="row justify-content-start">
                            <p>车次：</p>
                        </div>
                        <div class="row justify-content-start">
                            <p class="fs-4 fw-bold"><?= $trainName ?></p>
                        </div>
                        <div class="row justify-content-start">
                            <p>Date: <?= $date ?></p>
                        </div>
                        <div class="row justify-content-start">
                            <p>始发站: <?= $start_station ?> &nbsp; ~ &nbsp; 终点站：<?= $end_station ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col w-50 h-100 p-3">
                <div id="map" class="map h-100 w-100"></div>
            </div>
        </div>
        <hr/>
        <div class="row w-100" style="height: 70%; overflow: scroll">
            <table class="table-striped table-bordered border-secondary">
                <thead>
                <tr>
                    <th scope="col" class="text-center">车站</th>
                    <th scope="col" class="text-center">城市</th>
                    <th scope="col" class="text-center">到时</th>
                    <th scope="col" class="text-center">发时</th>
                    <th scope="col" class="text-center">停留</th>
                    <th scope="col" class="text-center">历时</th>
                    <th scope="col" class="text-center">里程</th>
                    <th scope="col" class="text-center">座位类型</th>
                    <!--                        <th colspan="2"-->
                    <!--                        ">-->
                    <!--                        <div class="dropdown d-flex flex-row justify-content-center align-items-center">-->
                    <!--                            <button class="btn btn-primary w-100" disabled id="seatTypeBtn"-->
                    <!--                                    style="border-bottom-right-radius:0; border-top-right-radius: 0;">硬座-->
                    <!--                            </button>-->
                    <!--                            <button class="btn btn-primary dropdown-toggle dropdown-toggle-split"-->
                    <!--                                    style="border-top-left-radius: 0; border-bottom-left-radius: 0" href="#"-->
                    <!--                                    role="button" id="dropDownSeatTypeBtn" data-bs-toggle="dropdown"-->
                    <!--                                    aria-expanded="false">-->
                    <!--                                <span class="visually-hidden"></span>-->
                    <!--                            </button>-->
                    <!--                            <ul class="dropdown-menu">-->
                    <!--                                <li><a class="dropdown-item" href="javascript:switchSeatToHardSeat()"-->
                    <!--                                       id="hardSeatDpItem">硬座</a></li>-->
                    <!--                                <li><a class="dropdown-item" href="javascript:switchSeatToSoftSeat()"-->
                    <!--                                       id="softSeatDpItem">软座</a></li>-->
                    <!--                                <li><a class="dropdown-item" href="javascript:switchSeatToHardBedTop()"-->
                    <!--                                       id="hardBedTopDpItem">硬卧上</a></li>-->
                    <!--                                <li><a class="dropdown-item" href="javascript:switchSeatToHardBedMid()"-->
                    <!--                                       id="hardBedMidDpItem">硬卧中</a></li>-->
                    <!--                                <li><a class="dropdown-item" href="javascript:switchSeatToHardBedDown()"-->
                    <!--                                       id="hardBedDownDpItem">硬卧下</a></li>-->
                    <!--                                <li><a class="dropdown-item" href="javascript:switchSeatToSoftBedTop()"-->
                    <!--                                       id="softBedTopDpItem">软卧上</a></li>-->
                    <!--                                <li><a class="dropdown-item" href="javascript:switchSeatToSoftBedDown()"-->
                    <!--                                       id="softBedDownDpItem">软卧下</a></li>-->
                    <!--                            </ul>-->
                    <!--                        </div>-->
                    <!--                        </th>-->
                    <!--                    </tr>-->
                    <!--                    <tr>-->
                    <th scope="col" class="text-center">票价</th>
                    <th scope="col" class="text-center">余票</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    $trainIdList = array_column($queryRes, 'train_id_ret');
                    $stationOrderList = array_column($queryRes, 'station_order');
                    $stationList = array_column($queryRes, 'station');
                    $stationIdList = array_column($queryRes, 'station_id');
                    $cityList = array_column($queryRes, 'city');
                    $cityIdList = array_column($queryRes, 'city_id');
                    $arriveTimeList = array_column($queryRes, 'arrive_time');
                    $leaveTimeList = array_column($queryRes, 'leave_time');
                    $stayTimeList = array_column($queryRes, 'stay_time');
                    $duranceList = array_column($queryRes, 'durance');
                    $distanceList = array_column($queryRes, 'distance');
                    $seatPriceList = array_column($queryRes, 'seat_price');
                    $seatNumsList = array_column($queryRes, 'seat_num');
                    $seatTypeList = array("硬座", "软座", "硬卧上", "硬卧中", "硬卧下", "软卧上", "软卧下");
                                echo <<<END
                <tr>
                    <td>$stationList[0]</td>
                    <td>$cityList[0]</td>
                    <td>$arriveTimeList[0]</td>
                    <td>$leaveTimeList[0]</td>
                    <td>$stayTimeList[0]</td>
                    <td>$duranceList[0]</td>
                    <td>$distanceList[0]</td>
                    <td>-</td>
                    <td id="seat-type-0">-</td>
                    <td id="seat-type-0">-</td>
                </tr>
END;
                    for ($i = 1; $i < count($stationList); $i++) {
                        $seatPriceListArray = explode(',', substr($seatPriceList[$i], 1, strlen($seatPriceList[$i]) - 2));
                        $seatNumsListArray = explode(',', substr($seatNumsList[$i], 1, strlen($seatNumsList[$i]) - 2));
                        for ($j = 0; $j < 7; $j++) {
                            if ($seatNumsListArray[$j] == 0) {
                                continue;
                            }
                                echo <<<END
                <tr>
                    <td>$stationList[$i]</td>
                    <td>$cityList[$i]</td>
                    <td>$arriveTimeList[$i]</td>
                    <td>$leaveTimeList[$i]</td>
                    <td>$stayTimeList[$i]</td>
                    <td>$duranceList[$i]</td>
                    <td>$distanceList[$i]</td>
                    <td>$seatTypeList[$j]</td>
                    <td id="seat-type-{$i}">$seatPriceListArray[$j]</td>
                    <td id="seat-type-{$i}"><a href="userGenerateOrder?trainId={$trainIdList[0]}&trainName={$trainName}&stationFromId={$stationIdList[0]}&stationFrom={$stationList[0]}&stationToId={$stationIdList[$i]}&stationTo={$stationList[$i]}&seat_type={$j}&order_date={$date}">$seatNumsListArray[$j]</a></td>
                </tr>
END;
                            }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php
ViewCtrl::includePageFooter();
?>