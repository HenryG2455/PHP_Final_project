<?php
require __DIR__ . '/../database/QuizAccessor.php';
require __DIR__.'/../entities/User.php';
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
    session_start();
    try {
        if(isset($_SESSION["permissionLevel"])) {
            $user = new User($_SESSION["username"],$_SESSION["permissionLevel"],$_SESSION["permissionLevel"]);
        }else {
            $user = new User("GUEST", "GUEST", "GUEST");
        }
        $results = json_encode($user, JSON_NUMERIC_CHECK);
        echo $results;
    } catch (Exception $e) {
        echo "ERROR " . $e->getMessage();
    }
}

function doDelete() {
    session_start();
    try {
        if(isset($_SESSION["permissionLevel"])) {
            $_SESSION["permissionLevel"] ="GUEST";
            $_SESSION["username"] ="GUEST";
            $user = new User($_SESSION["username"],$_SESSION["permissionLevel"],$_SESSION["permissionLevel"]);
        }else {
            $user = new User("GUEST", "GUEST", "GUEST");
        }
        $results = json_encode($user, JSON_NUMERIC_CHECK);
        echo $results;
    } catch (Exception $e) {
        echo "ERROR " . $e->getMessage();
    }
}