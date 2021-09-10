<?php
require 'post_class.php';
require 'db_class.php';
require 'logger_class.php';
require 'user.php';

$user = new user();
$user->start_session();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles/style-main.css">
    <title>Log In</title>
    <meta charset="utf-8">
</head>
<body>
<div class="form_block">

    <div class="SignUp_LogIn_block">
        <div class="passive_block">
            <a href="registration.html" target="_self">Sign Up</a>
        </div>
        <div class="active_block">
            <a href="index.php" target="_self">Log In</a>
        </div>
    </div>

    <form action="/action_authorization.php" id="registration_form" method="post">
        <h2>Log In</h2>

        <div class="fields_block">
            <input type="email" id="email" name="email" placeholder="Email Address*" value="" minlength="5"
                   maxlength="50" size="48" required><br><br>
            <input type="password" class="password" name="password" placeholder="Set A Password*" value="" minlength="8"
                   maxlength="32" size="48" required><br><br>
            <input type="checkbox" id="remember_check" name="remember_check" value="REMEMBER">
            <label id="confirm_check_label" for="remember_check"> Remember me</label><br><br>
            <input id="button_GET_STARTED" type="submit" value="GET STARTED">
        </div>
    </form>

</div>

</body>
</html>

















