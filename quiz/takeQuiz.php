<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Quiz</title>
        <!-- CSS only -->
        <style>
            .container{
                border: black thin solid;
                margin: 1rem;
                border-radius: 10px;
            }
            .btn{
                margin-left: 6.4rem;
                margin-bottom: 2rem;
            }
            h3{
                color: red;
            }

        </style>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">Home</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul  id="changer" class="navbar-nav me-auto mb-2 mb-lg-0">
                        <?php
                            require_once (__DIR__ . '/../database/QuizAccessor.php');
                            require_once (__DIR__ . '/../database/QuizResultAccessor.php');
                            require_once (__DIR__ . '/../utils/ChromePhp.php');
                            require_once (__DIR__ . '/../entities/Quiz.php');
                            require_once (__DIR__ . '/../entities/Question.php');
                            require_once (__DIR__ . '/../entities/User.php');
                            require_once (__DIR__ . '/../entities/QuizResult.php');
                            session_start();
                            $html="";
                            $username = $_SESSION["username"];
                            ChromePhp::log($username);
                            $html.="<li class=\"nav-item\"><a class=\"nav-link\">".$username."</a></li>";
                            echo $html;
                        ?>
                    </ul>

                </div>
            </div>
        </nav>
        <form action="takeQuiz.php" method="POST">
            <?php


            $now = new DateTime();
            $now->format('Y-m-d H:i:s');    // MySQL datetime format
            $now->getTimestamp();
            $_SESSION["currentTime"] =$now;

            $quizID="";
            $permissionLevel="";
            $username="";
            $html = "";
            $qa = new QuizAccessor();
            $ra = new QuizResultAccessor();

            if(isset($_GET["quizID"])){
                $permissionLevel = $_GET["permissionlevel"];
                $username = $_GET["username"];
                $quizID = $_GET["quizID"];
                $temp=$qa->getQuizByID($quizID);
                $quiz = $temp;
                $_SESSION["quiz"]= $quiz;
                $_SESSION["username"] = $username;
                $_SESSION["permissionLevel"] = $permissionLevel;
                //ChromePhp::log($_SESSION["currentTime"]);
                $_SESSION["startTime"] = $now;
                //ChromePhp::log($_SESSION["startTime"]);
            }else if(isset($_POST)){
                $quizResult=null;

                $quiz = $_SESSION["quiz"]->jsonSerialize();
                function allAnswered():bool{
                    $userAns=[];
                    $counter=0;
                    $quiz = $_SESSION["quiz"]->jsonSerialize();
                    $worked= false;
                    for($i=0;$i<count($quiz["questions"]);$i++){
                        if(!isset($_POST[$i])){
                            $worked= false;
                            break;
                        }else{
                            if($quiz["questions"][$i]->getAnswer() == $_POST[$i])
                                $counter++;
                            array_push($userAns,$_POST[$i]);
                            $worked= true;
                        }
                    }
                    $_SESSION["counter"]=$counter;
                    $_SESSION["userAns"]=$userAns;
                    return $worked;
                }
                ChromePhp::log(strval(allAnswered()));
                if(allAnswered()){
                    $quizTemp = $_SESSION["quiz"];
                    $username = $_SESSION["username"];
                    $permissionLevel = $_SESSION["permissionLevel"];
                    $numOfResults = $ra->getCountOfResults();

                    //Create quiz Result and add to session
                    $tempResultID = "QR-1";
                    $tempNum =(int)$numOfResults + 1;
                    $resultID=$tempResultID.$tempNum;
                    $quiz = $quizTemp;
                    ChromePhp::log($now->format('Y-m-d H:i:s'));
                    $user = new User($username,"Dummy", $permissionLevel);
                    $quizStartTime = $_SESSION["startTime"]->date;
                    $quizEndTime = $now->format('Y-m-d H:i:s');
                    $userAnswers =  $_SESSION["userAns"];
                    $scoreNumerator = $_SESSION["counter"];
                    $scoreDenominator = count($quiz->getQuestions());
                    $obj = new QuizResult($resultID, $quiz, $user, $quizStartTime, $quizEndTime, $userAnswers, $scoreNumerator, $scoreDenominator);
                    $_SESSION["quizResult"] = $obj;
                    ChromePhp::log($_SESSION["quizResult"]);
                    header('Location: results.php');
                }else{
                    echo"<span><h3>Please Answer all questions</h3></span>";
                    $quiz = $_SESSION["quiz"];
                }
            }else{
                $quiz = $_SESSION["quiz"];
            }// String of Quiz ID


            ChromePhp::log($quiz);
            //store quiz in the session

            $tempQuiz = $quiz->jsonSerialize();

            $length = count($tempQuiz["questions"]);
            //ChromePhp::log($tempQuiz);

            $html .="<h1>".$tempQuiz['quizTitle']."</h1>";
            for($i = 0; $i < $length; $i++){
                $tempquestion = $tempQuiz["questions"][$i];
                //ChromePhp::log($tempquestion);
                $question = $tempquestion->jsonSerialize();


                $html .= "<div class='container'><h2>Question ".($i+1)." : ".$question["questionID"]."</h2><br>"."<p>".$question["questionText"]."</p>";
                for($j=0; $j < count($question["choices"]);$j++){
                    $tempChoice = $question["choices"][$j];

                    $html .= "<input type='radio' id='".$tempChoice."' name='".($i)."' value='".$j."'>";
                    $html .= "<label class='p-2' for='".$tempChoice."'>".$tempChoice."</label><br>";
                }
                $html .= "</div>";
            }
            //$html = "<span></span>";

            $html .= "";
            echo $html;
            ?>
            <button class="btn btn-outline-primary" type="submit">Submit</button>
        </form>
        <!-- JavaScript Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
    </body>

</html>
