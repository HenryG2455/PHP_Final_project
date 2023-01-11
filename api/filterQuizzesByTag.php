<?php
require_once(__DIR__ . '/../database/QuizAccessor.php');

$QAcc = new QuizAccessor();

echo json_encode($QAcc->getAllQuizzes());


//$allQuizzes = $QAcc->getAllQuizzes();
//
//$filteredQuizzes = [];
//foreach ($allQuizzes as $quiz)
//{
//    foreach ($quiz['tags'] as $tag)
//    {
//        if ($tag['tagName'])
//            str_contains('abc', '')
//    }
//}
