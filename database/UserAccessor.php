<?php
//require_once 'ConnectionManager.php';
require_once 'dbconnect.php';
require_once(__DIR__ . '/../entities/User.php');
require_once(__DIR__ . '/../utils/ChromePhp.php');

class UserAccessor
{
    public function getAllUsers(): array
    {
        $result = [];
        $stmt = null;
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("select * from QuizAppUser where permissionLevel='USER'");
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dbresults as $r) {
                $username = $r['username'];
                $password = $r['password'];
                $obj = new User($username, $password, "USER");
                $result[] = $obj;
            }
        } catch (Exception $e) {
            $result = [];
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }

        return $result;
    }

    public function getAllQuizResults($username): array
    {
        $result = [];
        $stmt = null;
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("SELECT * FROM QuizResult WHERE username = :username");
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);
//            foreach ($dbresults as $r) {
//                $username = $r['username'];
//                $password = $r['password'];
//                $obj = new User($username, $password, "USER");
//                $result[] = $obj;
//            }
        } catch (Exception $e) {
            //$result = [];
            echo $e->getMessage()."<br>";
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }

        return $result;
    }

    public function getAllAccounts(): array
    {
        $result = [];
        $stmt = null;
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("select * from QuizAppUser");
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dbresults as $r) {
                $username = $r['username'];
                $password = $r['password'];
                $perm = $r['permissionLevel'];
                $obj = new User($username, $password, $perm);
                $result[] = $obj;
            }
        } catch (Exception $e) {
            $result = [];
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }

        return $result;
    }

    public function createUser($username, $password, $permissionLevel): bool
    {
        $worked = false;
        $encryptedPassword = password_hash($password, PASSWORD_DEFAULT); // encrypted password is stored in database

        try {
            $conn = connect_db();
            $stmt = $conn->prepare("INSERT INTO QuizAppUser (username, password, permissionLevel) VALUES (:username, :password, :permissionLevel)");
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $encryptedPassword);
            $stmt->bindParam(":permissionLevel", $permissionLevel);
            $stmt->execute();
            $success = $stmt->rowCount();
            $stmt->closeCursor();
            $worked = ($success === 1);
        } catch (Exception $ex) {
            echo $ex;
        }

        return $worked;
    }

    public function deleteUser($username): bool
    {
        $worked = false;

        try {
            $conn = connect_db();
            $stmt = $conn->prepare("DELETE FROM QuizAppUser WHERE username = :username");
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            $success = $stmt->rowCount();
            $stmt->closeCursor();
            $worked = ($success === 1);
        } catch (Exception $ex) {
            echo $ex;
        }

        return $worked;
    }

    public function getUser($username): object
    {
        $result = false;

        try {
            $conn = connect_db();
            $stmt = $conn->prepare("select * FROM QuizAppUser WHERE username = :username");
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //ChromePhp::log($dbresults);
            $result = new User($dbresults[0]["username"], $dbresults[0]["password"], $dbresults[0]["permissionLevel"]);
            $stmt->closeCursor();
        } catch (Exception $ex) {
            echo $ex;
        }
        return $result;
    }


    public function changePassword($User, $oldPassword, $newPassword): bool
    {
        //$user = new User($username, $oldPassword, $permissionLevel);
        if (!$User->isPasswordCorrect($oldPassword)) return false;

        $worked = false;
        $encryptedPassword = password_hash($newPassword, PASSWORD_DEFAULT); // encrypted password is stored in database

        try {
            $conn = connect_db();
            $stmt = $conn->prepare("UPDATE QuizAppUser SET password = :password WHERE username = :username");
            $stmt->bindParam(":username", $User->getUsername());
            $stmt->bindParam(":password", $encryptedPassword);
            $stmt->execute();
            $success = $stmt->rowCount();
            $stmt->closeCursor();
            $worked = ($success === 1);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

        return $worked;
    }
} // end of UserAccessor Class