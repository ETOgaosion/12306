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
        $startCity = $_POST['trainCityFromCityName'];
        $endCity = $_POST['trainCityToCityName'];
        self::sendQueryCity($startCity, $endCity);
    }

    #[NoReturn] public static function queryCityReverse(): void {
        $startCity = $_POST['trainCityToCityName'];
        $endCity = $_POST['trainCityFromCityName'];
        self::sendQueryCity($startCity, $endCity);
    }

    #[NoReturn] public static function queryTrain(): void {
        $trainName = $_POST['trainName'];
        $setOffDate = $_POST['trainSetOffDate'];
        $queryRes = UserQuery::querytrain($trainName, $setOffDate);
        ViewCtrl::includeView('/userQueryTrainRes', array(
            'trainName' =>  $trainName,
            'date' => $setOffDate,
            'start_station' => $queryRes[0]['station'],
            'end_station' => end($queryRes)['station'],
            'queryRes' => $queryRes
        ));
        die();
    }

    #[NoReturn] public static function queryTrainFromGet(): void {
        $trainName = $_GET['trainName'];
        $setOffDate = $_GET['date'];
        $queryRes = UserQuery::querytrain($trainName, $setOffDate);
        ViewCtrl::includeView('/userQueryTrainRes', array(
            'trainName' =>  $trainName,
            'date' => $setOffDate,
            'start_station' => $queryRes[0]['station'],
            'end_station' => end($queryRes)['station'],
            'queryRes' => $queryRes
        ));
        die();
    }

    /**
     * @param mixed $startCity
     * @param mixed $endCity
     * @return void
     */
    #[NoReturn] public static function sendQueryCity(mixed $startCity, mixed $endCity): void
    {
        $startDate = $_POST['trainCitySetOffDate'];
        $startTime = $_POST['trainCitySetOffTime'];
        $queryTransfer = isset($_POST['trainCityQueryTransfer']) && ($_POST['trainCityQueryTransfer'] == 'true');
        $queryRes = UserQuery::queryCity($startCity, $endCity, $startDate, $startTime, $queryTransfer);
        ViewCtrl::includeView('/userQueryCityRes', array(
            'date' => $startDate,
            'time' => $startTime,
            'start_city' => $startCity,
            'end_city' => $endCity,
            'queryRes' => $queryRes
        ));
        die();
    }
}