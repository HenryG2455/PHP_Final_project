<?php

session_start();
require __DIR__ . "/../database/UserAccessor.php";

$UsernameToChangePassword = $_POST['UsernameToChangePassword'];
$currentPassword = $_POST['currentPassword'];
$newPassword = $_POST['newPassword'];
$passwordsMatch = $newPassword == $_POST['newPasswordConfirmation'];
$IAmSuper = ($_POST['IAmSuper'] == 'IAmSuper') && ($_SESSION['permissionLevel'] == 'SUPER');



$acc = new UserAccessor();
$users = $acc->getAllAccounts();
$user = null;

//print_r($users);

foreach ($users as $temp) {
    if ($UsernameToChangePassword === $temp->getUsername()) {
        $user = $temp;
        break;
    }
}

echo "The user typed as username: ".$UsernameToChangePassword."<br>";
echo "The user typed as current password: ".$currentPassword."<br>";
echo "The user typed as old password: ".$_POST['newPassword']."<br>";

if (!$IAmSuper) echo "Only System Users can do it, and you are not.";
elseif (!$passwordsMatch) echo "new password doesn't match its confirmation";
elseif ($user === null) {
    echo "no such user";
} else {
    echo "Let's change this password: <br>";
    /// password stuff
    if ($acc->changePassword($user, $currentPassword, $newPassword)) {
        header("location: ../HomePages/SystemHomePage.php");
    } else {
        echo "wrong password";
    }
}

