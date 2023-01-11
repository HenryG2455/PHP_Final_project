<?php

require __DIR__ . '/../database/QuestionAccessor.php';

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


//function doGet() {
//    // individual
//    try {
//        $tagAcc = new TagAccessor();
//        $results = $tagAcc->getAllTags();
//        $results = json_encode($results, JSON_NUMERIC_CHECK);
//        echo $results;
//    } catch (Exception $e) {
//        echo "ERROR " . $e->getMessage();
//    }
//}

function doPost() {
    $questionID = $_POST['questionID'];
    $questionText = $_POST['questionText'];
    $choices = implode(" | ", $_POST['choices']);
    $answer = $_POST['answer'];
    $tag = $_POST['tag'];
    $questionAcc = new QuestionAccessor();

    $worked = $questionAcc->createQuestion($questionID, $questionText, $choices, $answer, $tag);
    if ($worked)
        header("location: ../HomePages/AdminHomePage.php");
    else
        header("location: ../errorPage.php");
}