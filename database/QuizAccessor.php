<?php

require_once('dbconnect.php');
require_once('QuestionAccessor.php');
require_once(__DIR__ . '/../entities/Quiz.php');
require_once (__DIR__ . '/../utils/ChromePhp.php');


class QuizAccessor
{
    public function getQuizByID($quizID): ?Quiz
    {
        $result = null;
        $stmt = null;
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("select * from Quiz where quizID = :quizID");
            $stmt->bindParam(":quizID", $quizID);
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($dbresults) !== 1) {
                throw new Exception("duplicate quizIDs found in Quiz table!");
            }

            $dbquiz = $dbresults[0];
            $questionAcc = new QuestionAccessor();
            $quizTitle = $dbquiz["quizTitle"];
            $questions = $questionAcc->getQuestionsForQuiz($quizID);
            $points = $this->getPointsForQuiz($quizID);
            $result = new Quiz($quizID, $quizTitle, $questions, $points);
        } catch (Exception $e) {
            $result = null;
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }

        return $result;
    }
    
    public function getQuizzesByTitle($quizTitle): ?Quiz
    {
        $result = null;
        $stmt = null;
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("select * from Quiz where quizTitle like '%:quizID%'");
            $stmt->bindParam(":quizID", $quizTitle);
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($dbresults) !== 1) {
                throw new Exception("duplicate quizIDs found in Quiz table!");
            }

            $dbquiz = $dbresults[0];
            $questionAcc = new QuestionAccessor();
            $quizTitle = $dbquiz["quizTitle"];
            $questions = $questionAcc->getQuestionsForQuiz($quizID);
            $points = $this->getPointsForQuiz($quizID);
            $result = new Quiz($quizID, $quizTitle, $questions, $points);
        } catch (Exception $e) {
            $result = null;
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }

        return $result;
    }

    public function getAllQuizzes(): array
    {
        $results = [];
        $stmt = null;
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("select * from Quiz");
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $questionAcc = new QuestionAccessor();

            foreach ($dbresults as $r) {
                $quizID = $r["quizID"];
                $quizTitle = $r['quizTitle'];
                $questions = $questionAcc->getQuestionsForQuiz($quizID);
                $points = $this->getPointsForQuiz($quizID);
                $obj = new Quiz($quizID, $quizTitle, $questions, $points);
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

    private function getPointsForQuiz($quizID): array
    {
        $points = [];
        $stmt = null;
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("select points from QuizQuestion where quizID = :quizID");
            $stmt->bindParam(":quizID", $quizID);
            $stmt->execute();
            $dbpoints = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$points = [];
            foreach ($dbpoints as $p) {
                $points[] = intval($p);
            }
        } catch (Exception $e) {
            $points = [];
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }

        return $points;
    }
    
    public function getResultsByQuery($string):array{
        //ChromePhp::log($string);
        $conn = connect_db();
        $stmt = $conn->prepare($string);
        $stmt->execute();
        $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //ChromePhp::log($dbresults);
        return $dbresults;
        
    }

//    public function getNumQuestionsOfAQuizByTag(int $quizID): array
//    {
//        $quizzes = [];
//        $stmt = null;
//        try {
//            $conn = connect_db();
//            $stmt = $conn->prepare("SELECT Q.quizID, quizTitle, COUNT(QuizQuestion.questionID) AS numOfQuestions
//                                                FROM QuizQuestion join Quiz Q on Q.quizID = QuizQuestion.quizID
//                                                JOIN Question Q2 on Q2.questionID = QuizQuestion.questionID
//                                                JOIN QuestionTag QT on Q2.questionID = QT.questionID
//                                                JOIN Tag T on T.tagID = QT.tagID
//                                            WHERE T.tagID = :quizID
//                                            GROUP BY Q.quizID, quizTitle
//                                            ORDER BY quizID");
//            $stmt->bindParam(":quizID", $quizID);
//            $stmt->execute();
//            $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//        } catch (Exception $e) {
//            echo $e->getMessage();
//        } finally {
//            if (!is_null($stmt)) {
//                $stmt->closeCursor();
//            }
//        }
//
//        return $quizzes;
//    }


//    public function getNumQuestionsOfAQuizAndTag(): array
//    {
//        $quizzes = [];
//        $stmt = null;
//        try {
//            $conn = connect_db();
//            $stmt = $conn->prepare("SELECT Q.quizID, quizTitle, COUNT(QuizQuestion.questionID) AS numOfQuestions, T.tagID
//                                                FROM QuizQuestion join Quiz Q on Q.quizID = QuizQuestion.quizID
//                                                JOIN Question Q2 on Q2.questionID = QuizQuestion.questionID
//                                                JOIN QuestionTag QT on Q2.questionID = QT.questionID
//                                                JOIN Tag T on T.tagID = QT.tagID
//                                            GROUP BY Q.quizID, quizTitle
//                                            ORDER BY quizID");
//            $stmt->execute();
//            $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//        } catch (Exception $e) {
//            echo $e->getMessage();
//        } finally {
//            if (!is_null($stmt)) {
//                $stmt->closeCursor();
//            }
//        }
//
//        return $quizzes;
//    }
}