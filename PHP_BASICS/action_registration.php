<?php
//session_start();
//if (array_key_exists('user_id', $_COOKIE)) {
//  header("refresh:0;url=mypage.php");
//}

require 'post_class.php';
require 'db_class.php';
require 'logger_class.php';
require 'user.php';

$user = new user();
$user->register();

