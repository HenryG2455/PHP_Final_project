<?php
require __DIR__ . '/../database/QuizAccessor.php';
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
    if (isset($_GET['quizID'])) {
        $id = $_GET['quizID'];
        //ChromePhp::log("Sorry, individual gets not allowed!");
        try {
            $ea = new quizAccessor();
            $results = $ea->getQuizzesByTitle($id);
            $results = json_encode($results, JSON_NUMERIC_CHECK);
            echo $results;
        } catch (Exception $e) {
            echo "ERROR " . $e->getMessage();
        }
    }
//    else if(isset ($_GET['tags'])){
//        $tags = explode("|", $_GET["tags"]);
//        try {
//            ChromePhp::log($tags);
//            $ea = new quizAccessor();
//        } catch (Exception $e) {
//            echo "ERROR " . $e->getMessage();
//        }
//    }
    // collection
    else {
        try {
            $ea = new quizAccessor();
            $results = $ea->getAllQuizzes();
            $results = json_encode($results, JSON_NUMERIC_CHECK);
            echo $results;
        } catch (Exception $e) {
            echo "ERROR " . $e->getMessage();
        }
    }
}