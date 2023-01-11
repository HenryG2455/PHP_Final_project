<!DOCTYPE html>
<html lang="en">
<head>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="../scripts/SystemHome.js"></script>
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
                    <a class="nav-link active" aria-current="page" id="logout">Log Out</a>
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





<!--Create a User Modal-->
    <!-- Button trigger modal -->
<!--    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#CreateAccountModal">-->
<!--        Create a User-->
<!--    </button>-->

    <!-- Modal -->
    <div class="modal fade" id="CreateAccountModal" tabindex="-1" aria-labelledby="CreateAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="CreateAccountModalLabel">Create a User and Set Permission level</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!--Form-->
                <form class="modal-body container border rounded p-5 bg-white" method="post" action="../api/createAccount.php">
<!--                        Username-->
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Username</label>
                        <input name="username" type="text" class="form-control w-75" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                        <?php
                        if (isset($_SESSION["usernameAttempt"]))
                            echo "<div class='form-text text-danger'>That username (".$_SESSION["usernameAttempt"].") is already taken. Please, choose another</div>";
                        ?>
                    </div>


                    <!--                        Permission level radio buttons-->
                    <div class="mb-3">
                        <label class="d-block">Permission Level</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="permissionLevel" id="USERRadioButton" value="USER" checked>
                            <label class="form-check-label" for="USERRadioButton">User</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="permissionLevel" id="ADMINRadioButton" value="ADMIN">
                            <label class="form-check-label" for="ADMINRadioButton">Admin</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="permissionLevel" id="SUPERRadioButton" value="SUPER">
                            <label class="form-check-label" for="SUPERRadioButton">Super</label>
                        </div>
                    </div>



                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input name="password" type="password" class="form-control w-75" id="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="passwordConfirmation" class="form-label">Password Confirmation</label>
                        <input name="password" type="password" class="form-control w-75" id="passwordConfirmation" required>
                    </div>

                    <input type="hidden" name="IAmSuper" value="IAmSuper">
                    <button type="submit" class="btn btn-primary" id="CreateAccountButton">Create Account</button>
                </form>

            </div>
        </div>
    </div>
<!--End of Create User Modal-->




<div  class="d-flex justify-content-around p-3 my-5">
    <h2 class="text-center my-4">List of all accounts</h2>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#CreateAccountModal">Create a User</button>
</div>

<!--       TABLE      -->
<table class="table table-hover table-striped my-3 mx-auto w-75">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Username</th>
        <th scope="col">Permission Level</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    <tbody>

    <?php
        require_once(__DIR__ . '/../database/UserAccessor.php');
        $accessor = new UserAccessor();
        $allAccounts = $accessor->getAllAccounts();

        //foreach ($allAccounts as $account)
        for ($i = 0; $i < count($allAccounts); $i++)
        {
            echo "<tr>
                    <th scope='row'>".($i + 1)."</th>
                    <td>{$allAccounts[$i]->getUsername()}</td>
                    <td>{$allAccounts[$i]->getPermissionLevel()}</td>
                    <td class='d-flex justify-content-between'>
                        <!-- Button trigger modal -->
                        <button type='button' class='btn btn-outline-info m-1' data-bs-toggle='modal' data-bs-target='#ChangePasswordOf{$allAccounts[$i]->getUsername()}Modal'>Change Password</button>
                        
                        <form method='post' action='../api/deleteAccount.php'>
                            <input type='hidden' name='username' value='{$allAccounts[$i]->getUsername()}'>
                            <button type='submit' class='btn btn-outline-danger m-1'>Delete Account</button>
                        </form>
                    </td>
                    
                                    <!-- Modal -->
                    <div class='modal fade' id='ChangePasswordOf{$allAccounts[$i]->getUsername()}Modal' tabindex='-1' aria-labelledby='ChangePasswordOf{$allAccounts[$i]->getUsername()}ModalLabel' aria-hidden='true'>
                        <div class='modal-dialog modal-dialog-centered modal-dialog-scrollable'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h1 class='modal-title fs-5' id='ChangePasswordOf{$allAccounts[$i]->getUsername()}ModalLabel'>Create a User and Set Permission level</h1>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                </div>
                
                                <!--Form-->
                                <div class='modal-body border rounded container p-5 bg-white'>
                                    <form  method='post' action='../api/changePassword.php'>                                
                                        <!--Username-->
                                        <div class='mb-3'>
                                            <label for='UsernameToChangePassword' class='form-label'>Username</label>
                                            <input type='text' class='form-control w-75' id='UsernameToChangePassword' disabled value='{$allAccounts[$i]->getUsername()}'>
                                            <input type='hidden' name='UsernameToChangePassword' value='{$allAccounts[$i]->getUsername()}'>
                                            
                                        </div>
                    
                    
                                        <div class='mb-3'>
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






<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>