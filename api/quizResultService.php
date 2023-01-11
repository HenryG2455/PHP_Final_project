<?php
require __DIR__ . '/../database/QuizResultAccessor.php';
require_once(__DIR__ . '/../utils/ChromePhp.php');

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
    if (isset($_GET['resultID'])) {
        $id = $_GET['resultID'];
        //ChromePhp::log("Sorry, individual gets not allowed!");
        try {
            $ea = new quizResultAccessor();
            $results = $ea->getQuizzesByTitle($id);
            $results = json_encode($results, JSON_NUMERIC_CHECK);
            echo $results;
        } catch (Exception $e) {
            echo "ERROR " . $e->getMessage();
        }
    }   else if (isset($_GET['username'])) {
        $username = $_GET['username'];
        //ChromePhp::log("Sorry, individual gets not allowed!");
        try {
            $ea = new quizResultAccessor();
            $results = $ea->getResultsByUser($username);
            $results = json_encode($results, JSON_NUMERIC_CHECK);
            echo $results;
        } catch (Exception $e) {
            echo "ERROR " . $e->getMessage();
        }
    }
    else {
        try {
            $ea = new quizResultAccessor();
            $results = $ea->getAllResults();
            $results = json_encode($results, JSON_NUMERIC_CHECK);
            echo $results;
        } catch (Exception $e) {
            echo "ERROR " . $e->getMessage();
        }
    }
}