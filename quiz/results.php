<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Results Page</title>
    <style>
        .true{
            background-color: green;
        }
        .false{
            background-color: red;
        }
        .container{
            border: black thin solid;
            margin: 1rem;
            border-radius: 10px;
        }
        .btn{
            margin-left: 6.4rem;
            margin-bottom: 2rem;
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
                    $html.="<li class='nav-item\'><a class='nav-link'>".$username."</a></li>";
                    echo $html;
                    ?>
                </ul>

            </div>
        </div>
    </nav>
    <form action="../index.php" >
        <?php
        $ra = new QuizResultAccessor();
        $tempQuiz = $_SESSION["quiz"]->jsonSerialize();
        $result = $_SESSION["quizResult"]->jsonSerialize();
        //ChromePhp::log($result);
        $happened = $ra->addQuizResult($result);
        ChromePhp::log($happened);
        $length = count($tempQuiz["questions"]);

        $html = "";
        $counter = 0;
        for($i = 0; $i < $length; $i++){
            $question = $tempQuiz["questions"][$i]->jsonSerialize();
            $html .= "<div class='container'><h2>Question ".($i+1)."</h2><br>"."<p>".$question["questionText"]."</p>";
            for($j=0; $j < count($question["choices"]);$j++){
                $tempChoice = $question["choices"][$j];

                $html .= "<input type='radio' id='".$tempChoice."' name='".($i)."' value='".$j."'>";
                $html .= "<label class='p-2' for='".$tempChoice."'>".$tempChoice."</label><br>";



            }
            $answer = $question["answer"];
            ChromePhp::log($result);
            if($result['answers'][$i]  == (String)$question["answer"]){
                $html .="<p class ='true'>Your Answer: ".$question["choices"][$result['answers'][$i]]." </p><br>";
                $html .= "<p>Correct answer: ".$question["choices"][$answer]."</p>";
                $counter ++;
            }else{
                $html .="<p class ='false'>Your Answer: ".$question["choices"][$result['answers'][$i]]." </p><br>";
                $html .= "<p>Correct answer: ".$question["choices"][$answer]."</p>";
            }
            $html .= "</div>";
        }

        $html .= "Results: ".$counter." / ".$length ;
        echo $html;
        ?>
        <button class="btn btn-outline-primary" type="submit">Submit</button>
    </form>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>

</body>
</html>

