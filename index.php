<!DOCTYPE html>
<html lang="en">
<head>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <meta charset="UTF-8">
    <script src="./scripts/main.js"></script>
    <link rel="stylesheet" href="mainStyleSheet.css">
    <title>PHP Project</title>
</head>
<body>

<!--          NAVBAR         -->
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul  id="changer" class="navbar-nav me-auto mb-2 mb-lg-0">

            </ul>
            
            <div class="d-flex" role="search">
                <div class="form-floating">
                    <select class="form-select" id="quizTagsSelect" aria-label="Floating label select example">
                    </select>
                    <label for="quizTagsSelect">Search for a Quiz</label>
                </div>
                <button class="btn btn-outline-success" type="button" id="searchByTagButton">Search</button>
            </div>
        </div>
    </div>
</nav>
<div class="d-flex justify-content-end" role="tagSearch" id="tagSearch">

    <p id="tagLabel">Search by these Tags:</p>
    <p id="tags"></p>
</div>
<h3 class="text-center my-5">Quizzes</h3>
<button id="refresh" class="btn btn-outline-success mx-5">Refresh Table</button>
<table class="table table-striped table-hover container mt-5 w-75">
    <thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">Quiz Title</th>
        <th scope="col">Number of Questions</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    <tbody>

    </tbody>
</table>







<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>