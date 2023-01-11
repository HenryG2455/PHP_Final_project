<?php

require_once 'dbconnect.php';
require_once(__DIR__ . '/../entities/Question.php');
require_once('TagAccessor.php');
require_once(__DIR__ . '/../entities/Tag.php');


class QuestionAccessor
{
    public function getResultsByQuery($query): array
    {
        $results = [];
        $stmt = null;
        $dbresults = null;
        try {
            $conn = connect_db();
            $stmt = $conn->prepare($query);
            $stmt->execute();
//            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo $e->getMessage();
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }

//        foreach ($dbresults as $r) {
//            $questionID = $r["questionID"];
//            $questionText = $r["questionText"];
//            $choices = explode(" | ", $r["choices"]);
//            $answer = $r["answer"];
//            $tags = $r["tags"];
//
//
//            $obj = new Question($questionID, $questionText, $choices, $answer, $tags);
//            $results[] = $obj;
//        }

        return $results;
    }

    public function getQuestionsForQuiz($quizID): array
    {
        $results = [];
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("select questionID, questionText, choices, answer from QuizQuestion join Question using(questionID) where quizID = :quizID");
            $stmt->bindParam(":quizID", $quizID);
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $ta = new TagAccessor();

            foreach ($dbresults as $r) {
                $questionID = $r["questionID"];
                $questionText = $r["questionText"];
                $choices = explode("|", $r["choices"]);
                $answer = intval($r["answer"]);
                $tags = $ta->getTagsForQuestion($r["questionID"]);
                $obj = new Question($questionID, $questionText, $choices, $answer, $tags);
                $results[] = $obj;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            $results = [];
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }
        return $results;
    }

    public function getAllQuestions(): array
    {
        $results = [];
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("select questionID, questionText, choices, answer from Question");
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $tagAccessor = new TagAccessor();

            foreach ($dbresults as $r) {
                $questionID = $r["questionID"];
                $questionText = $r["questionText"];
                $choices = explode("|", $r["choices"]);
                $answer = intval($r["answer"]);
                $tags = $tagAccessor->getTagsForQuestion($r["questionID"]);
                $obj = new Question($questionID, $questionText, $choices, $answer, $tags);
                $results[] = $obj;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            $results = [];
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }
        return $results;
    }

    public function getQuestionsByTags(string $tags): array
    {
        $arrayOfTags = implode(",", explode("-", $tags));

        $queryString = "SELECT * FROM question
                        WHERE questionID IN (
                                            SELECT questionID FROM questiontag
                                            WHERE tagID IN ($arrayOfTags)
                                            )";
        return $this->getResultsByQuery($queryString);
    }


    public function createQuestion($questionID, $questionText, $choices, $answer, $tag): bool
    {
        $QuestionInsertionWorked = true;

        try {
            $conn = connect_db();
            $stmt = $conn->prepare("CALL create_new_question(:questionID, :questionText, :choices, :answer, :tag)");
            $stmt->bindParam(":questionID", $questionID);
            $stmt->bindParam(":questionText", $questionText);
            $stmt->bindParam(":choices", $choices);
            $stmt->bindParam(":answer", $answer);
            $stmt->bindParam(":tag", $tag);
            $stmt->execute();
            $stmt->closeCursor();
        } catch (Exception $ex) {
            $QuestionInsertionWorked = false;
            echo $ex->getMessage();
        }

        return $QuestionInsertionWorked;
    }

















//    public function getAllQuestions(): array
//    {
//        $results = [];
//        try {
//            $conn = connect_db();
//            $stmt = $conn->prepare("select questionID, questionText, choices, answer from Question");
//            $stmt->execute();
////            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
////            $ta = new TagAccessor();
////            foreach ($dbresults as $r) {
////                $questionID = $r["questionID"];
////                $questionText = $r["questionText"];
////                $choices = explode("|", $r["choices"]);
////                $answer = intval($r["answer"]);
////                $tags = $ta->getTagsForQuestion($r["questionID"]);
////                $obj = new Question($questionID, $questionText, $choices, $answer, $tags);
////                $results[] = $obj;
////            }
//
//            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        } catch (Exception $e) {
////            $results = [];
//            echo $e->getMessage();
//        } finally {
//            if (!is_null($stmt)) {
//                $stmt->closeCursor();
//            }
//        }
//        return $results;
//    }
}