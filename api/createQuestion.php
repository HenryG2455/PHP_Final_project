<?php

require __DIR__ . "/../database/QuestionAccessor.php";

$questionID = $_POST['questionID'];
$questionText = $_POST['questionText'];
$choices = implode(" | ", $_POST['choices']);
$answer = $_POST['answer'];
$tag = $_POST['tag'];

echo $questionID."<br>";
echo $questionText."<br>";
echo $choices."<br>";
echo $answer."<br>";
echo $tag."<br>";

$questionAcc = new QuestionAccessor();
$allQuestions = $questionAcc->getAllQuestions();
$question = null;

foreach ($allQuestions as $temp) {
    if ($questionID === $temp->getQuestionID()) {
        $question = $temp;
        break;
    }
}


if ($question === null)
{
    echo "id is available";

    $worked = $questionAcc->createQuestion($questionID, $questionText, $choices, $answer, $tag);
    if ($worked)
        header("location: ../HomePages/AdminHomePage.php");
    else
        header("location: ../errorPage.php");

}
else
    echo "This ID already Exists";
