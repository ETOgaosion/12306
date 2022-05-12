<?php

use app\tools\Session;
use app\controllers\ViewCtrl;

ViewCtrl::includePageHeader(array('pageTitle' => "user_main", 'assetsDir' => "assets/", 'login' => true));
$assetsDir = 'assets/';
?>

    <!-- free map api: OpenLayers -->
    <!-- Map css api link -->
    <link href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.14.1/css/ol.css"
          rel="stylesheet" type="text/css">

    <!-- Map js api link -->
    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.14.1/build/ol.js"
            type="text/javascript"></script>

    <!-- HERE map api -->
    <!-- Map css api link -->
    <!--<link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css" />-->
    <!-- Map js api link -->
    <!--<script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-core.js"></script>-->
    <!--<script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-service.js"></script>-->
    <!--<script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-ui.js"></script>-->
    <!--<script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js"></script>-->

    <div class="d-flex align-items-center justify-content-center position-absolute start-0 end-0"
         style="top: 75px; bottom: 100px">
        <div class="d-flex align-items-center justify-content-center" style="height: 60%; width: 80%">
            <div class="row w-100 h-100">
                <div class="col-7 h-100 pe-3">
                    <div class="row h-100">
                        <div id="map" class="map h-100 w-100"></div>
                    </div>
                </div>
                <div class="col-5 h-100 d-flex flex-column align-items-center justify-content-center">
                    <div class="row h-100 w-100">
                        <div class="row flex-row align-items-center bg-light h-100" style="border-radius: 1rem">
                            <div class="nav col-4 nav-pills h-100 flex-column justify-content-center align-items-center"
                                 id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <button class="nav-link active" id="v-pills-city-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-city" type="button" role="tab"
                                        aria-controls="v-pills-city" aria-selected="true">两地查询
                                </button>
                                <button class="nav-link" id="v-pills-train-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-train" type="button" role="tab"
                                        aria-controls="v-pills-train" aria-selected="false">列车查询
                                </button>
                            </div>
                            <div class="tab-content col-8 h-100 p-3" id="v-pills-tabContent">
                                <div class="tab-pane fade show active h-100" id="v-pills-city" role="tabpanel"
                                     aria-labelledby="v-pills-city-tab">
                                    <form class="h-100 d-flex flex-column justify-content-evenly"
                                          id="queryTrainByCityForm" action="userQueryCity" method="post">
                                        <div class="row align-items-center p-1">
                                            <div class="col-5 align-items-center justify-content-center">
                                                <label for="trainCityFromCityName" class="col-form-label">出发城市</label>
                                            </div>
                                            <div class="col-7 align-items-center justify-content-center">
                                                <input type="text" name="trainCityFromCityName"
                                                       id="trainCityFromCityName" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row align-items-center p-1">
                                            <div class="col-5 align-items-center justify-content-center">
                                                <label for="trainCityToCityName" class="col-form-label">到达城市</label>
                                            </div>
                                            <div class="col-7 align-items-center justify-content-center">
                                                <input type="text" name="trainCityToCityName" id="trainCityToCityName"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="row align-items-center p-1">
                                            <div class="col-5 align-items-center justify-content-center">
                                                <label for="trainCitySetOffDate" class="col-form-label">出发日期</label>
                                            </div>
                                            <div class="col-7 align-items-center justify-content-center">
                                                <input type="date" name="trainCitySetOffDate" id="trainCitySetOffDate"
                                                       class="form-control"
                                                       value="<?php echo date('Y-m-d', strtotime('+1 day', time())); ?>">
                                            </div>
                                        </div>
                                        <div class="row align-items-center p-1">
                                            <div class="col-5 align-items-center justify-content-center">
                                                <label for="trainCitySetOffTime" class="col-form-label">出发时间</label>
                                            </div>
                                            <div class="col-7 align-items-center justify-content-center">
                                                <input type="time" name="trainCitySetOffTime" id="trainCitySetOffTime"
                                                       class="form-control"
                                                       value="<?php echo date('H:i', strtotime('00:00')); ?>">
                                            </div>
                                        </div>
                                        <div class="row flex-row justify-content-center align-items-center p-1">
                                            <div class="w-50 d-flex flex-row justify-content-center align-items-center">
                                                <button type="submit" formmethod="post" class="btn btn-primary"
                                                        form="queryTrainByCityForm">Submit
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade h-100" id="v-pills-train" role="tabpanel"
                                     aria-labelledby="v-pills-train-tab">
                                    <form class="h-100 d-flex flex-column justify-content-evenly"
                                          id="queryTrainByTrainForm" action="userQueryTrain" method="post">
                                        <div class="row align-items-center p-1">
                                            <div class="col-5 align-items-center justify-content-center">
                                                <label for="trainFromCityName" class="col-form-label">车次序号</label>
                                            </div>
                                            <div class="col-7 align-items-center justify-content-center">
                                                <input type="text" name="trainFromCityName" id="trainName"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="row align-items-center p-1">
                                            <div class="col-5 align-items-center justify-content-center">
                                                <label for="trainSetOffDate" class="col-form-label">出发日期</label>
                                            </div>
                                            <div class="col-7 align-items-center justify-content-center">
                                                <input type="date" name="trainSetOffDate" id="trainSetOffDate"
                                                       class="form-control"
                                                       value="<?php echo date('Y-m-d', strtotime('+1 day', time())); ?>">
                                            </div>
                                        </div>
                                        <div class="row flex-row justify-content-center align-items-center p-1">
                                            <div class="w-50 d-flex flex-row justify-content-center align-items-center">
                                                <button type="submit" formmethod="post" class="btn btn-primary"
                                                        form="queryTrainByTrainForm">Submit
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- free map api: OpenLayers -->
    <!-- File js link -->
    <script src="<?= $assetsDir ?>js/mapAPI.js"></script>

    <!-- HERE map api -->
    <!-- File js link -->
    <!--<script src="-->< ?//= $assetsDir ? ><!--js/mapAPI.js"></script>-->

<?php
ViewCtrl::includePageFooter();
?>