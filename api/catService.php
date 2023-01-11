<?php

require_once(__DIR__ . '/../database/TagAccessor.php');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === "GET") {
    doGet();
} else if ($method === "POST") {
    doPost();
} else if ($method === "DELETE") {
    doDelete();
} else if ($method === "PUT") {
    doPut();
}


function doGet() {
    // individual
    try {
        $tagAcc = new TagAccessor();
        $results = $tagAcc->getAllTags();
        $results = json_encode($results, JSON_NUMERIC_CHECK);
        echo $results;
    } catch (Exception $e) {
        echo "ERROR " . $e->getMessage();
    }
}