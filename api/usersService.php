<?php
require __DIR__ . '/../database/UserAccessor.php';

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
    if (isset($_GET['itemid'])) {
        // Individual gets not implemented.
        echo "Sorry, individual gets not allowed!";
    }
    // collection
    else {
        try {
            $userAcc = new UserAccessor();
            $results = $userAcc->getAllUsers();
            $results = json_encode($results, JSON_NUMERIC_CHECK);
            echo $results;
        } catch (Exception $e) {
            echo "ERROR " . $e->getMessage();
        }
    }
}