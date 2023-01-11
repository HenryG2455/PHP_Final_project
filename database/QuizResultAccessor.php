<?php

require_once('dbconnect.php');
require_once('QuizAccessor.php');
require_once('UserAccessor.php');
require_once(__DIR__ . '/../entities/QuizResult.php');
require_once(__DIR__ . '/../entities/Quiz.php');
require_once(__DIR__ . '/../entities/User.php');
require_once(__DIR__ . '/../utils/ChromePhp.php');

class QuizResultAccessor
{
    public function addQuizResult($quizResult): bool
    {
        ChromePhp::log($quizResult);
        $worked = false;
        $ansString="";
        for($i=0; $i<count($quizResult["answers"]);$i++){
            $ans = $quizResult["answers"][$i];
            if($i+1 == count($quizResult["answers"])){
                $ansString.=$ans;
            }else{
                $ansString.=$ans."|";
            }
        }

        $query = "insert into QuizResult values ('".$quizResult["resultID"]."','".$quizResult["quiz"]->getQuizID()."','".$quizResult["user"]->getUsername()."','".$quizResult["startTime"]."','".$quizResult["endTime"]."','".$ansString."',".$quizResult["scoreNumerator"].",".$quizResult["scoreDenominator"].")";
        ChromePhp::log($query);
        try {
            $conn = connect_db();
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $success = $stmt->rowCount();
            $stmt->closeCursor();
            $worked = ($success === 1);
        } catch (Exception $e) {
            //$results = [];
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }
        return $worked;
    }

    public function getResultsByQuery($query): array
    {
        $results = [];
        $stmt = null;
        $dbresults = null;
        try {
            $conn = connect_db();
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            //$results = [];
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }

        $quizAcc = new QuizAccessor();
        $userAcc = new UserAccessor();

        foreach ($dbresults as $r) {
            $resultID = $r["resultID"];
            $quiz = $quizAcc->getQuizByID($r["quizID"]);
            $username = $userAcc->getUser($r["username"]) ;
            $quizStartTime = $r["quizStartTime"];
            $quizEndTime = $r["quizEndTime"];
            $userAnswers = explode("|",$r["userAnswers"]);
            $scoreNumerator = $r["scoreNumerator"];
            $scoreDenominator = $r["scoreDenominator"];
            $obj = new QuizResult($resultID, $quiz, $username, $quizStartTime, $quizEndTime, $userAnswers, $scoreNumerator, $scoreDenominator);
            $results[] = $obj;
        }

        return $results;
    }

    public function getAllResults(): array {
        $results = [];
        $stmt = null;
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("select * from quizresult");
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $quizAcc = new QuizAccessor();
            $userAcc = new UserAccessor();
            foreach ($dbresults as $r) {
                $resultID = $r["resultID"];
                $quiz = $quizAcc->getQuizByID($r["quizID"]);
                $username = $userAcc->getUser($r["username"]) ;
                $quizStartTime = $r["quizStartTime"];
                $quizEndTime = $r["quizEndTime"];
                $userAnswers = explode("|",$r["userAnswers"]);
                $scoreNumerator = (int)$r["scoreNumerator"];
                $scoreDenominator = (int)$r["scoreDenominator"];
                $obj = new QuizResult($resultID, $quiz, $username, $quizStartTime, $quizEndTime, $userAnswers, $scoreNumerator, $scoreDenominator);
                $results[] = $obj;
            }
        } catch (Exception $e) {
            $results = [];
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }

        return $results;

    }
    public function getResultsByUser($username): array
    {
        return $this->getResultsByQuery("select * from QuizResult where username = '" . $username . "'");
    }
    public function getResultsByID($id): array
    {
        return $this->getResultsByQuery("select * from QuizResult where resultID = '" . $id . "'");
    }

    public function getCountOfResults(): string {
        $results = 0;
        $stmt = null;
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("SELECT count(*) as count FROM quizresult;");
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //ChromePhp::log($dbresults);
            $results = $dbresults[0]['count'];

        } catch (Exception $e) {
            $results = [];
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }

        return $results;

    }


    public function getResultsByScore(int $min, int $max): array
    {
        $realMin = min($min, $max);
        $realMax = max($min, $max);

        $queryString = "SELECT *  FROM `quizresult` WHERE scoreNumerator / scoreDenominator * 100 between " . $realMin . " and " . $realMax;
        return $this->getResultsByQuery($queryString);
    }

    public function getResultsByDate($min, $max): array
    {
        $realMin = min($min, $max);
        $realMax = max($min, $max);

        $queryString = "SELECT * FROM quizresult WHERE quizEndTime BETWEEN '".$realMin."' AND '".$realMax."'";
        return $this->getResultsByQuery($queryString);
    }

    public function getResultsByTags(string $tags): array
    {
//        $arrayOfTags = explode("-", $tags);
        $arrayOfTags = implode(",", explode("-", $tags));

        $queryString = "SELECT * FROM quizresult
                        WHERE quizID IN (
                                        SELECT quizID FROM quizquestion
                                        WHERE questionID IN (
                                                SELECT questionID FROM questiontag
                                                WHERE tagID IN ($arrayOfTags)
                                            )
                                        )";
        return $this->getResultsByQuery($queryString);
    }

//    public function getResultsByUser($username): array
//    {
//        return $this->getResultsByQuery("select * from QuizResult where username = '" . $username . "'");
//    }
    public function getAggregatedResultsByTags(string $tags): array
    {
        $arrayOfTags = implode(",", explode("-", $tags));

        $queryString = "SELECT q.quizID, quizTitle, MAX(scoreNumerator / scoreDenominator) * 100 AS Max, AVG(scoreNumerator / scoreDenominator) * 100 AS AVG, MIN(scoreNumerator / scoreDenominator) * 100 AS Min
                        FROM quizresult JOIN quiz q on q.quizID = quizresult.quizID
                        WHERE q.quizID IN (
                                            SELECT quizID FROM quizquestion
                                            WHERE questionID IN (
                                                SELECT questionID FROM questiontag
                                                WHERE tagID IN ($arrayOfTags)
                                            )
                                        )
                        GROUP BY quizID";


        $stmt = null;
        $dbresults = null;
        try {
            $conn = connect_db();
            $stmt = $conn->prepare($queryString);
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo $e->getMessage();
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }

        return $dbresults;
    }
}