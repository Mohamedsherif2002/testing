<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="libs/css/main.css" >
</head>
<body>
<?php
ob_start();
require_once('includes/load.php');

if ($session->isUserLoggedIn(true)) {
    redirect('home.php', false);
}

// include_once('layouts/header.php');
?>
<div class="login-page">
    <div class="text-center">
        <h1>Login Panel</h1>
    </div>

    <form method="post" action="auth.php" class="clearfix">
        <div class="form-group" >
            <label for="username" class="control-label">Username</label>
            <input type="name" class="form-control" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <label for="Password" class="control-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-danger" style="border-radius:5%">Login</button>
        </div>
        
    </form>
    <div class="text-center">
        <a href="sign_in.php">Create New Account</a>
    </div>
    <div class="text-center">
            <a href="home.php">Sign in later</a>
        </div>
    
</div>

<?php include_once('layouts/footer.php'); ?>

</body>
</html>
