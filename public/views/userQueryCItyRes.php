<?php
use app\controllers\ViewCtrl;
ViewCtrl::includePageHeader(array('pageTitle' => "query_res", 'assetsDir' => "assets/", 'login' => true));
$assetsDir = 'assets/';
$date = date('Y-m-d', time());
$time = date('H:i', time());;
$start_city = "北京";
$end_city = "郑州";
?>

<!-- free map api: OpenLayers -->
<!-- Map css api link -->
<link href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.14.1/css/ol.css" rel="stylesheet"
      type="text/css">

<!-- Map js api link -->
<script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.14.1/build/ol.js"
        type="text/javascript"></script>

<!-- File js link -->
<script src="<?= $assetsDir?>js/userQueryRes.js"></script>

<div class="d-flex align-items-center justify-content-center position-absolute start-0 end-0"
     style="top: 75px; bottom: 100px">
    <div class="h-100 w-75 d-flex flex-column align-items-center justify-content-start bg-light p-5"
         style="--bs-bg-opacity: 0.8">
        <div class="row flex-row justify-content-center align-items-center w-100" style="height: 50px;">
            <p class="fs-2 fw-bold text-center">车次查询信息</p>
        </div>
        <div class="row w-100" style="height: 25%">
            <div class="col w-50 h-100 p-3">
                <div class="row w-100" style="height: 200px;">
                    <div class="row flex-column justify-content-center align-items-center w-100 ps-5">
                        <div class="row justify-content-start">
                            <p>Date: <?= $date ?></p>
                        </div>
                        <div class="row justify-content-start">
                            <p>Time: <?= $time ?></p>
                        </div>
                        <div class="row justify-content-start">
                            <p>起始城市: <?= $start_city ?> &nbsp; ~ &nbsp; 目的城市：<?= $end_city ?></p>
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
            <table class="table-light table-striped table-bordered border-secondary">
                <thead>
                <tr>
                    <th scope="col" rowspan="2" class="text-center">车次编号</th>
                    <th scope="col" rowspan="2" class="text-center">起始站</th>
                    <th scope="col" rowspan="2" class="text-center">目的站</th>
                    <th scope="col" rowspan="2" class="text-center">发时</th>
                    <th scope="col" rowspan="2" class="text-center">到时</th>
                    <th scope="col" rowspan="2" class="text-center">历时</th>
                    <th scope="col" rowspan="2" class="text-center">里程</th>
                    <th colspan="2"">
                    <div class="dropdown d-flex flex-row justify-content-center align-items-center">
                        <button class="btn btn-primary w-100" disabled id="seatTypeBtn" style="border-bottom-right-radius:0; border-top-right-radius: 0;">硬座</button>
                        <button class="btn btn-primary dropdown-toggle dropdown-toggle-split" style="border-top-left-radius: 0; border-bottom-left-radius: 0" href="#" role="button" id="dropDownSeatTypeBtn" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:switchSeatToHardSeat()" id="hardSeatDpItem">硬座</a></li>
                            <li><a class="dropdown-item" href="javascript:switchSeatToSoftSeat()" id="softSeatDpItem">软座</a></li>
                            <li><a class="dropdown-item" href="javascript:switchSeatToHardBedTop()" id="hardBedTopDpItem">硬卧上</a></li>
                            <li><a class="dropdown-item" href="javascript:switchSeatToHardBedMid()" id="hardBedMidDpItem">硬卧中</a></li>
                            <li><a class="dropdown-item" href="javascript:switchSeatToHardBedDown()" id="hardBedDownDpItem">硬卧下</a></li>
                            <li><a class="dropdown-item" href="javascript:switchSeatToSoftBedTop()" id="softBedTopDpItem">软卧上</a></li>
                            <li><a class="dropdown-item" href="javascript:switchSeatToSoftBedDown()" id="softBedDownDpItem">软卧下</a></li>
                        </ul>
                    </div>
                    </th>
                </tr>
                <tr>
                    <th scope="col" class="text-center">票价</th>
                    <th scope="col" class="text-center">余票</th>
                </tr>
                </thead>
                <tbody>
                <?php
                // echo all ticket price and left tickets info about every kind of tickets
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- free map api: OpenLayers -->
<!-- File js link -->
<script src="<?= $assetsDir ?>js/mapAPI.js"></script>


<?php
ViewCtrl::includePageFooter();
?>