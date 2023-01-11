<?php

require_once(__DIR__ . '/../database/QuizAccessor.php');
require_once (__DIR__ . '/../utils/ChromePhp.php');

$tags = explode(",", $_GET["tags"]); // array of tags
ChromePhp::log($tags);

        try {
            $qa = new QuizAccessor();
            $search = "";
            if(count($tags)>2){
               $search= "select DISTINCT a.quizID 
                from
                  quiz a join quizquestion b on a.quizID = b.quizID 
                  join question c on b.questionID = c.questionID 
                  join questiontag d on c.questionID = d.questionID 
                  join tag e on d.tagID = e.tagID
                 where 
                   e.tagName = '".$tags[0]."' 
                   OR 
                   e.tagName = '".$tags[1]."';";
            }else{
                $search= "select DISTINCT a.quizID 
                from
                  quiz a join quizquestion b on a.quizID = b.quizID 
                  join question c on b.questionID = c.questionID 
                  join questiontag d on c.questionID = d.questionID 
                  join tag e on d.tagID = e.tagID
                 where 
                   e.tagName = '".$tags[0]."';";
            }
            
            $results = $qa->getResultsByQuery($search);
            
            $quizzes = [];
            foreach($results as $r){
                $item=$qa->getQuizByID($r["quizID"]);
                ChromePhp::log($item);
                array_push($quizzes,$item);
            }
            //ChromePhp::log($quizzes);
            $resultsJson = json_encode($quizzes, JSON_NUMERIC_CHECK);
            echo $resultsJson;
        } catch (Exception $e) {
            echo "ERROR " . $e->getMessage();
        }
    