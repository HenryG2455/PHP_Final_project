<?php

require __DIR__ . "/../database/UserAccessor.php";
require_once (__DIR__ . "/../utils/ChromePhp.php");

$username = $_POST['username'];
$password = $_POST['password'];

$acc = new UserAccessor();
$users = $acc->getAllAccounts();
$user = null;

//print_r($users);

foreach ($users as $temp) {
    if ($username === $temp->getUsername()) {
        $user = $temp;
        break;
    }
}

echo "The user typed as username: ".$username."<br>";
echo "The user typed as password: ".$password."<br>";

if ($user === null) {
    echo "no such user";
} else {
    echo "user ok <br>";
    /// password stuff
    if ($user->isPasswordCorrect($password)) {
        echo "password matches";
        session_start();
        $_SESSION["permissionLevel"] = $user->getPermissionLevel();
        $_SESSION["username"] = $user->getUsername();
        //ChromePhp::log($_SESSION["permissionLevel"], $_SESSION["username"]);
        if ($_SESSION["permissionLevel"] == "SUPER")
        {
            header("location: ../HomePages/SystemHomePage.php");
        }
        elseif ($_SESSION["permissionLevel"] == "ADMIN")
        {
            header("location: ../HomePages/AdminHomePage.php");
        }
        elseif ($_SESSION["permissionLevel"] == "USER")
        {
            header("location: ../index.php");
        }
    } else {
        echo "wrong password";
    }
}

