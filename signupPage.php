<!DOCTYPE html>
<html lang="en">
<head>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="scripts/main.js"></script>
    <meta charset="UTF-8">
    <title>Sign Up</title>
</head>
<body class="bg-light">
<h1 class="p-3 m-3 text-center">Hello there! Nice to see you joining us</h1>
<form class="container border rounded p-5 bg-white w-50" method="post" action="api/createAccount.php">
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Username</label>
        <input name="username" type="text" class="form-control w-75" id="exampleInputEmail1" aria-describedby="emailHelp" required>
        <div class="form-text">We'll never share your data with anyone else.</div>

<?php
session_start();
if (isset($_SESSION["usernameAttempt"]))
    echo "<div class='form-text text-danger'>That username (".$_SESSION["usernameAttempt"].") is already taken. Please, choose another</div>";
?>


    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input name="password" type="password" class="form-control w-75" id="password" required>
    </div>
    <div class="mb-3">
        <label for="passwordConfirmation" class="form-label">Password Confirmation</label>
        <input name="password" type="password" class="form-control w-75" id="passwordConfirmation" required>
    </div>

    <button type="submit" class="btn btn-primary" id="SignInButton">Create Account</button>

    <div class="form-text mt-5 mb-3">Already have an account? Log in <a href="loginPage.html">here</a></div>
</form>


<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>