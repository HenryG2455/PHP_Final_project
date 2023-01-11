<?php

session_start();
require __DIR__ . "/../database/UserAccessor.php";

$username = $_POST['username'];

$acc = new UserAccessor();
$users = $acc->getAllAccounts();
$user = null;

foreach ($users as $temp) {
    if ($username === $temp->getUsername()) {
        $user = $temp;
        break;
    }
}

//echo "The username is: ".$username."<br>";
//echo "This username has taken: ".count($acc->getAllQuizResults($username))." quizzes already<br>";

if ($user === null) {
    echo "no such user<br>";
}
elseif ($username == $_SESSION["username"])
{
    echo "You cannot delete yourself <br>";
    return;
}
elseif ($user->getPermissionLevel() === "USER" && count($acc->getAllQuizResults($username)) > 0)
{
    echo "You cannot delete user who have taken a quiz before <br>";
    return;
}
else {
    echo "user ok";
    if ($_SESSION["permissionLevel"] == "SUPER") {
        echo "I'm trying to delete now<br>";
        $acc->deleteUser($username);
        header("location: ../HomePages/SystemHomePage.php");
    } else {
        echo "Oops, something went wrong with deleting";
    }
}

