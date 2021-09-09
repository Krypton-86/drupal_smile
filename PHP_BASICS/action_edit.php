<?php
require 'post_class.php';
require 'db_connect.php';
require 'user.php';
// Prepare query, check validity

$_post = new post_class();
$user = new user();
$user->edit($db_conn, $_post);
