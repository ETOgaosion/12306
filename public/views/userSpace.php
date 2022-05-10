<?php
use app\controllers\ViewCtrl;
ViewCtrl::includePageHeader(array('pageTitle' => "user_Space", 'assetsDir' => "assets/", 'login' => true));
$assetsDir = 'assets/';
$userName = "Blue Space";
$userEmail = "gaoziyuan19@mails.ucas.ac.cn";
$userRealName = "Gao Ziyuan";
$userTelNum = array(1,9,8,0,1,1,9,0,3,6,5);
$orderIDList = array(0);
$dateList = array(date('Y-m-d', time()));
$startStationList = array("北京南");
$arriveStationList = array("郑州东");
$priceList = array(0);
$orderStatusList = array('COMPLETE');
?>

<div class="d-flex align-items-center justify-content-center position-absolute start-0 end-0"
     style="top: 75px; bottom: 100px">
    <div class="h-100 w-75 d-flex flex-column align-items-center justify-content-start bg-light p-5"
         style="--bs-bg-opacity: 0.8">
        <div class="row w-100 d-flex flex-row align-items-center justify-content-center p-5" style="height: 50px">
            <p class="fw-bold fs-2 text-center">用户空间</p>
        </div>
        <div class="row h-25 w-100 p-5 justify-content-center">
            <div class="nav nav-tabs border-dark d-flex flex-row align-items-center justify-content-center">
                <p>Settings</p>
            </div>
            <div class="row w-50 h-100 d-flex flex-column justify-content-center align-items-center">
                <div class="row" style="height: 50px">
                    <div class="col-4 d-flex justify-content-start align-items-center">
                        <p>UserName:</p>
                    </div>
                    <div class="col-7 d-flex justify-content-start align-items-center">
                        <p><?= $userName?></p>
                    </div>
                    <div class="col-1 d-flex justify-content-center align-items-center">
                        <a href="#" id="editUserName"><i class="bi bi-pencil-square fs-5 fw-bold"></i></a>
                    </div>
                </div>
                <div class="row" style="height: 50px">
                    <div class="col-4 d-flex justify-content-start align-items-center">
                        <p>Password:</p>
                    </div>
                    <div class="col-1 d-flex justify-content-center align-items-center">
                        <a href="#" id="editPassword"><i class="bi bi-pencil-square fs-5 fw-bold"></i></a>
                    </div>
                </div>
                <div class="row" style="height: 50px">
                    <div class="col-4 d-flex justify-content-start align-items-center">
                        <p>UserRealName:</p>
                    </div>
                    <div class="col-7 d-flex justify-content-start align-items-center">
                        <p><?= $userRealName?></p>
                    </div>
                    <div class="col-1 d-flex justify-content-center align-items-center">
                        <a href="#" id="editUserRealName"><i class="bi bi-pencil-square fs-5 fw-bold"></i></a>
                    </div>
                </div>
                <div class="row" style="height: 50px">
                    <div class="col-4 d-flex justify-content-start align-items-center">
                        <p>UserTelNum:</p>
                    </div>
                    <div class="col-7 d-flex justify-content-start align-items-center">
                        <p><?php
                            foreach ($userTelNum as $num) {
                                echo $num;
                            }
                            ?></p>
                    </div>
                    <div class="col-1 d-flex justify-content-center align-items-center">
                        <a href="#" id="editUserTelNum"><i class="bi bi-pencil-square fs-5 fw-bold"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row w-100 p-5 flex-column justify-content-start position-relative" style="height: 65%">
            <div class="nav nav-tabs border-dark d-flex flex-row align-items-center justify-content-center">
                <p>Orders Query</p>
            </div>
            <form class="row w-100 pt-2" style="height: 100px">
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
            <div class="row w-100 position-absolute" style="overflow: scroll; top: 200px; bottom: 0">
                <table class="table-light table-striped table-bordered">
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

<?php
ViewCtrl::includePageFooter();
?>