<?php
require_once 'core/Logs.php';
//Проверка частых обращений
session_start();

$time=microtime(1);
if (!isset($_SESSION["arr_time"])) $_SESSION["arr_time"]=array(0,0,0);
$min_time=min($_SESSION["arr_time"]);

if ($time-$min_time < 3.5)
    Logs::logger(0);

$min_index=array_search($min_time,$_SESSION["arr_time"]);
$_SESSION["arr_time"][$min_index]=$time;

require_once 'api/TestApi.php';

$api = new TestApi();
//Запуск работы API
try {
    $api = new TestApi();
    echo $api->start();
} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}