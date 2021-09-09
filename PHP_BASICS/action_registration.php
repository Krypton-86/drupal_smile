<?php
session_start();
if (array_key_exists('user_id', $_COOKIE)) {
  header("refresh:0;url=mypage.php");
}
require 'post_class.php';
require 'db_connect.php';
require 'user.php';

$_post = new post_class();
$user = new user();
$user->register($db_conn, $_post);
//mysqli_close($db_conn);


