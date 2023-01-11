<?php

require_once(__DIR__ . '/../database/QuizResultAccessor.php');
require_once(__DIR__ . '/../utils/ChromePhp.php');

/*
 * Important Note:
 *
 * We know that $_GET["scoremin"] $_GET["scoremin"] and  exists because .htaccess creates it.
 */

$minDate = $_GET["mindate"];
$maxDate = $_GET["maxdate"];
//ChromePhp::log($minDate." | ".$maxDate);
try {
    //ChromePhp::log($minDate." | ".$maxDate);
    $qra = new QuizResultAccessor();
    $results = $qra->getResultsByDate($minDate, $maxDate);
//    $results = $qra->getResultsByDate('2022-01-01', '2022-07-30');
    $resultsJson = json_encode($results, JSON_NUMERIC_CHECK);
    //ChromePhp::log($resultsJson);
    echo $resultsJson;

} catch (Exception $e) {
    echo "ERROR " . $e->getMessage();
}