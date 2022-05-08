<?php
$pageTitle = "admin_Main";
$assetsDir = "../assets/";
$login = true;
$totalOrder = 0;
$totalPrice = 0;
$hotTrains = array("1055", "1055", "1055", "1055", "1055", "1055", "1055", "1055", "1055", "1055");
$registerUserIdList = array("007");
$registerUserNameList = array("Blue Space");
$userName = "007";
$userRealName = "Blue Space";
$userEmail = "gaoziyuan19@mails.ucas.ac.cn";
$userTelNumber = array(1, 9, 8, 0, 1, 1, 9, 0, 3, 6, 5);
$orderIDList = array(0);
$dateList = array(date('Y-m-d', time()));
$startStationList = array("北京南");
$arriveStationList = array("郑州东");
$priceList = array(0);
$orderStatusList = array('COMPLETE');
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
                <div class="col-1 h-100 d-flex flex-column justify-content-center align-items-center">
                    <a class="btn btn-primary" href="#">Search Info</a>
                </div>
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
                                if ($orderStatusList[$i] == 2){
                                    echo <<<END
                            <a href="#" id="cancelOrder"><i class="bi bi-trash fw-bold fs-5"></i></a>
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
    </div>
</div>