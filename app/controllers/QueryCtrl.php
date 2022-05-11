<?php
namespace  app\controllers;
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

use app\models\UserQuery;
use app\controllers\ViewCtrl;
use JetBrains\PhpStorm\NoReturn;

class QueryCtrl
{
    #[NoReturn] public static function queryCity(): void {
        $startCity = $_POST('trainCityFromCityName');
        $endCity = $_POST('trainCityToCityName');
        $startDate = $_POST('trainCitySetOffDate');
        $startTime = $_POST('trainCitySetOffTime');
        $queryRes = UserQuery::queryCity($startCity, $endCity, $startDate, $startTime);
        ViewCtrl::includeView('/userQueryCityRes', array(
            'date' => $startDate,
            'time' => $startTime,
            'start_city' => $startCity,
            'end_city' => $endCity,
            'queryRes' => $queryRes
        ));
        die();
    }

    #[NoReturn] public static function queryTrain(): void {
        $trainName = $_POST['trainName'];
        $setOffDate = $_POST['trainSetOffDate'];
        $queryRes = UserQuery::querytrain($trainName, $setOffDate);
        ViewCtrl::includeView('/userQueryTrainRes', array(
            'trainName' =>  $trainName,
            '$date' => $setOffDate,
            '$start_station' => $queryRes['station_from_name'][0],
            '$end_station' => end($queryRes['station_to_name']),
            'queryRes' => $queryRes
        ));
        die();
    }
}