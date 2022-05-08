<?php
$pageTitle = "order_generate";
$assetsDir = "../assets/";
$login = true;
$trainName = "1055";
$date = date('Y-m-d', time());
$start_station = "北京南";
$end_station = "郑州东";
$remain_tickets = 5;
$userName = "007";
$userRealName = "Blue Space";
$userTelNum = array(1,9,8,0,1,1,9,0,3,6,5);
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
                        <div class="col-sm-6 d-flex flex-column justify-content-center align-items-start">
                            <p class="fw-bold fs-3 text-start">Order Sheets</p>
                        </div>
                        <div class="col-sm-3 d-flex flex-column justify-content-center align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="includeMyselfCheckBox" checked>
                                <label class="form-check-label" for="includeMyselfCheckBox">
                                    Include Myself.
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-3 d-flex flex-column justify-content-center align-items-center">
                            <button type="button" class="btn btn-primary add-new"><i class="bi bi-plus"></i> Add New</button>
                        </div>
                    </div>
                </div>
                <form class="row w-100" style="overflow: scroll; height: 75%;" id="orderUsersForm">
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
                            <td class="align-top p-3" style="height: 50px"><?= $userName?></td>
                            <td class="align-top p-3" style="height: 50px"><?= $userRealName?></td>
                            <td class="align-top p-3" style="height: 50px"><?php foreach ($userTelNum as $num) {
                                    echo $num;
                                }
                                ?></td>
                            <td class="align-top p-3" style="height: 50px">
                                <a class="add" title="Add" data-toggle="tooltip"><i class="bi bi-plus-square fw-bold fs-5"></i></a>
                                <a class="edit" title="Edit" data-toggle="tooltip"><i class="bi bi-pencil-square fw-bold fs-5"></i></a>
                                <a class="delete" title="Delete" data-toggle="tooltip"><i class="bi bi-trash fw-bold fs-5"></i></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
                <div class="row w-100 d-flex flex-row justify-content-center align-items-center" style="height: 10%">
                    <button form="orderUsersForm" type="submit" class="btn btn-primary w-25">
                        Submit
                    </button>
                </div>
                <div></div>
            </div>
        </div>
    </div>
</div>