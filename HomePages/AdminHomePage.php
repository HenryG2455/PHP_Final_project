<!DOCTYPE html>
<html lang="en">
<head>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
<!--    TODO: make a AdminHome.js just like SystemHome.js-->
    <!--    <script src="../scripts/SystemHome.js"></script>-->
    <link rel="stylesheet" href="AdminHomePage.css">
    <script src="../scripts/AdminHome.js"></script>

    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body class="container">
<?php
session_start();
if(!isset($_SESSION["username"])) header("location: ../loginPage.html")
?>

<h1>Home Page</h1>

<?php
echo "<h2>Welcome, ".$_SESSION["username"].". You have the permission of a ".$_SESSION['permissionLevel']."</h2>";
?>



<!--          NAVBAR         -->
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="../index.php">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../loginPage.html">Log Out</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="../signupPage.php">Sign Up</a>
                </li>


            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search by Tag" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>

<!--Query by User-->
<div class="container border rounded p-3 my-4 mx-auto" id="searchForQuizResultsByUserContainer">
    <h4>Search for Quiz Results by User</h4>
    <select class="form-select w-25 d-inline m-2" aria-label="Select User" id="selectUser">
        <option selected>Open this select menu</option>
    </select>
    <button type="button" class="btn btn-outline-success m-2" id="selectUserButton">Search Quizzes</button>

    <table class="table table-hover table-striped" id="quizTable"></table>
</div>

<!--Query by Score Range-->
<div class="container border rounded p-3 my-4 mx-auto" id="searchForQuizResultsByRangeContainer">
    <h4>Search for Quiz Results by Result Range</h4>
    <div class="container d-flex justify-content-around">
        <div class="form-floating mb-3 w-25">
            <input type="number" min="0" max="100" value="0" class="form-control" id="minRange" placeholder="min range">
            <label for="minRange">Min Score</label>
        </div>

        <div class="form-floating mb-3 w-25">
            <input type="number" min="0" max="100" value="0" class="form-control" id="maxRange" placeholder="max range">
            <label for="maxRange">Max Score</label>
        </div>

    </div>


    <button type="button" class="btn btn-outline-success m-2" id="selectRangeButton">Search Quizzes</button>

    <table class="table table-hover table-striped" id="quizTableByRange"></table>
</div>

<!--Query by Date Range-->
<div class="container border rounded p-3 my-4 mx-auto" id="searchForQuizResultsByDateRangeContainer">
    <h4>Search for Quiz Results by Result Range</h4>
    <div class="container d-flex justify-content-around">
        <div class="form-floating mb-3 w-25">
            <input type="date" class="form-control" id="minDate" placeholder="min date">
            <label for="minDate">Min Date</label>
        </div>

        <div class="form-floating mb-3 w-25">
            <input type="date" class="form-control" id="maxDate" placeholder="max date">
            <label for="maxDate">Max Date</label>
        </div>

    </div>


    <button type="button" class="btn btn-outline-success m-2" id="selectDateRangeButton">Search Quizzes</button>

    <table class="table table-hover table-striped" id="quizTableByDateRange"></table>
</div>

<!--Query Quiz Results by Tag-->
<div class="container border rounded p-3 my-4 mx-auto" id="searchForQuizResultsByTagsContainer">
    <h4>Search for Quiz Results by Tags</h4>

    <div id="tagsSelectorContainer"></div>

    <button type="button" class="btn btn-outline-success m-2" id="selectTagsButton">Search Quizzes</button>

    <table class="table table-hover table-striped" id="quizTableByTags"></table>
</div>


<!--Query Aggregate Quiz Results by Tag-->
<div class="container border rounded p-3 my-4 mx-auto" id="searchForAggregateQuizResultsByTagsContainer">
    <h4>Search for Aggregate Quiz Results by Tags</h4>

    <div class="form-floating">
        <select class="form-select w-25" id="tagsFloatingSelectAggregate" aria-label="Floating label select example">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
        </select>
        <label for="tagsFloatingSelectAggregate">Select how many tags you want to search</label>
    </div>

    <div id="" class="ms-5 my-3 questionsTagsSelectorContainer"></div>

    <button type="button" class="btn btn-outline-success m-2 selectQuestionTagsButton" id="">Search Quizzes</button>

    <table class="table table-hover table-striped quizQuestionTableByTags" id=""></table>
</div>


<!--Query Questions by Tag-->
<div class="container border rounded p-3 my-4 mx-auto" id="searchForQuestionsByTagsContainer">
    <h4>Search for Questions by Tags</h4>

    <div class="form-floating">
        <select class="form-select w-25" id="tagsFloatingSelect" aria-label="Floating label select example">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
        </select>
        <label for="tagsFloatingSelect">Select how many tags you want to search</label>
    </div>

    <div id="questionsTagsSelectorContainer" class="ms-5 my-3"></div>

    <button type="button" class="btn btn-outline-success m-2" id="selectQuestionTagsButton">Search Quizzes</button>

    <table class="table table-hover table-striped" id="quizQuestionTableByTags"></table>
</div>












<!-- Modal -->
<div class="modal fade" id="CreateQuestionModal" tabindex="-1" aria-labelledby="CreateQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="CreateQuestionModalLabel">Create a Question</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!--Form-->
            <form class="modal-body container border rounded p-5 bg-white" method="POST" action="../api/createQuestion.php">
                <!--                        Question  ID-->
                <div class="mb-3">
                    <label for="questionID" class="form-label">Question ID</label>
                    <input name="questionID" type="text" class="form-control w-75" id="questionID" required pattern="^QN-\d\d\d\d$">
                    <div id="questionIDHelp" class="form-text">The question ID must follow the patern: 'QN-NNNN'</div>
                </div>


                <!--                        Question Text  -->
                <div class="form-floating mb-3">
                    <textarea class="form-control" name="questionText" placeholder="Question Text" id="questionText" style="height: 100px" required></textarea>
                <!--be honest, you also liked this smooth transition-->
                    <label for="questionText">Question Text</label>
                </div>



                <!--                        Question Choices  -->
                <div class="mb-3 ms-3">
                    <label for="choice0" class="form-label">Choices</label>
                    <input type="text" name="choices[]" class="form-control" id="choice0" required>
                    <label for="choice1" class="form-label">Choices</label>
                    <input type="text" name="choices[]" class="form-control" id="choice1" required>
                    <label for="choice2" class="form-label">Choices</label>
                    <input type="text" name="choices[]" class="form-control" id="choice2" required>
                    <label for="choice3" class="form-label">Choices</label>
                    <input type="text" name="choices[]" class="form-control" id="choice3" required>
<!--                    <div id="choicesHelp" class="form-text">Write the choices as the following pattern: 'choice1 | choice2 | choice3 ...'</div>-->
                </div>

                <!--                        Answer  -->
                <div class="mb-3">
                    <label for="answer" class="form-label">Answer</label>
                    <input type="text" class="form-control" name="answer" id="answer" required>
                </div>


                <!--                        Tag  -->
                <div class="mb-3">
                    <label for="tag">Pick a tag</label>
                    <select class="form-select" aria-label="Default select example" name="tag" id="tag">
                        <?php
                        require_once(__DIR__ . '/../database/TagAccessor.php');

                        $tagAcc = new TagAccessor();
                        $tags = $tagAcc->getAllTags();

                        foreach ($tags as $tag)
                        {
                            echo "<option value='{$tag->getTagID()}'>".explode('::', $tag->getTagName())[1]."</option>";
                        }
                        ?>
                    </select>
                </div>



                <input type="hidden" name="IAmAdmin" value="IAmAdmin">
                <button type="submit" class="btn btn-primary" id="CreateQuestionButton">Create Question</button>
            </form>

        </div>
    </div>
</div>
<!--End of Create User Modal-->




<!--  LIST OF ALL QUESTIONS  -->
<div class="section border rounded py-3 my-5">
    <div  class="d-flex justify-content-around p-3 my-5">
        <h2 class="text-center my-4">List of all questions</h2>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#CreateQuestionModal">Create a Question</button>
    </div>

    <!--       TABLE      -->
    <table class="table table-hover table-striped my-3 mx-auto container">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Question</th>
            <th scope="col">Choices</th>
            <th scope="col">Answer Index</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>

        <?php
        require_once(__DIR__ . '/../database/QuestionAccessor.php');
        $accessor = new QuestionAccessor();
        $allQuestions = $accessor->getAllQuestions();

        foreach ($allQuestions as $question)
        {
            echo "<tr>
                    <th scope='row'>".$question->getQuestionID()."</th>
                    <td>{$question->getQuestionText()}</td>
                    <td>".implode('|', $question->getChoices())."</td>
                    <td>{$question->getAnswer()}</td>
                    <td class='d-flex justify-content-between'>
                        <!-- Button trigger modal -->
                        <button type='button' class='btn btn-outline-info m-1' data-bs-toggle='modal' data-bs-target='#ChangePasswordOf{$question->getQuestionID()}Modal'>Change Password</button>
                        
                        <form method='post' action='../api/deleteQuestion.php'>
                            <input type='hidden' name='username' value='{$question->getQuestionID()}'>
                            <button type='submit' class='btn btn-outline-danger m-1'>Delete Question</button>
                        </form>
                    </td>
                    
                                    <!-- Modal -->
                    <div class='modal fade' id='ChangePasswordOf{$question->getQuestionID()}Modal' tabindex='-1' aria-labelledby='ChangePasswordOf{$question->getQuestionID()}ModalLabel' aria-hidden='true'>
                        <div class='modal-dialog modal-dialog-centered modal-dialog-scrollable'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h1 class='modal-title fs-5' id='ChangePasswordOf{$question->getQuestionID()}ModalLabel'>Edit the Question</h1>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                </div>
                
                                <!--Form-->
                                <div class='modal-body border rounded container p-5 bg-white'>
                                    <form  method='post' action='../api/changePassword.php'>                                
                                        <!--Username-->
                                        <div class='mb-3'>
                                            <label for='UsernameToChangePassword' class='form-label'>Question ID</label>
                                            <input type='text' class='form-control w-75' id='UsernameToChangePassword' disabled value='{$question->getQuestionID()}'>
                                            <input type='hidden' name='UsernameToChangePassword' value='{$question->getQuestionID()}'>
                                            
                                        </div>
                    
                    
                                        <div class='mb-3 ms-3'>
                                            <label for='currentPassword' class='form-label'>Current Password</label>
                                            <input name='currentPassword' type='password' class='form-control w-75' id='currentPassword' required>
                                        </div>
                    
                                        <div class='mb-3'>
                                            <label for='newPassword' class='form-label'>Password</label>
                                            <input name='newPassword' type='password' class='form-control w-75' id='newPassword' required>
                                        </div>
                                        <div class='mb-3'>
                                            <label for='newPasswordConfirmation' class='form-label'>Password Confirmation</label>
                                            <input name='newPasswordConfirmation' type='password' class='form-control w-75' id='newPasswordConfirmation' required>
                                        </div>
                    
                                        <input type='hidden' name='IAmSuper' value='IAmSuper'>
                                        <button type='submit' class='btn btn-primary' id='ChangePasswordButton'>Change Password</button>
                                    </form>
                                </div>
                                

                            </div>
                        </div>
                    </div>                                               
                </tr>";
        }
        ?>
        </tbody>
    </table>
</div>




<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>