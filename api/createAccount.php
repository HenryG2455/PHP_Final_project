<?php

require __DIR__ . "/../database/UserAccessor.php";

$username = $_POST['username'];
$password = $_POST['password'];
$permissionLevel = $_POST["permissionLevel"] ?? "USER";

$acc = new UserAccessor();
$allAccounts = $acc->getAllAccounts();
$user = null;

//print_r($users);

foreach ($allAccounts as $temp) {
    if ($username === $temp->getUsername()) {
        $user = $temp;
        break;
    }
}

session_start();

if ($user === null)
{
    echo "username is available";
    unset($_SESSION['usernameAttempt']);

    if (!isset($_POST["IAmSuper"]))
    {
        $_SESSION["permissionLevel"] = $permissionLevel;
        $_SESSION["username"] = $username;
    }

    $worked = $acc->createUser($username, $password, $permissionLevel);
    if ($worked)
        if($permissionLevel == "SUPER"){
            header("location: ../HomePages/SystemHomePage.php");
        }else if($permissionLevel == "ADMIN"){
            header("location: ../HomePages/AdminHomePage.php");
        }else{
            header("location: ../index.php");
        }
    else
        header("location: ../errorPage.php");

}
else
{
    echo "username already taken";
    $_SESSION["usernameAttempt"] = $username;

    if (!isset($_POST["IAmSuper"]))
        header("location: ../signupPage.php");
    else
        header("location: ../HomePages/SystemHomePage.php");
}

