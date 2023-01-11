<?php

require_once(__DIR__ . '/../database/QuizResultAccessor.php');

/*
 * Important Note:
 *
 * We know that $_GET["tags"] $_GET["tags"] and  exists because .htaccess creates it.
 */

$tags = $_GET["tags"];

try {
    $qra = new QuizResultAccessor();
    $results = $qra->getAggregatedResultsByTags($tags);
    $resultsJson = json_encode($results, JSON_NUMERIC_CHECK);
    echo $resultsJson;

} catch (Exception $e) {
    echo "ERROR " . $e->getMessage();
}