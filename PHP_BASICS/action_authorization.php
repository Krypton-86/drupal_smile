<?php
session_start();

require 'post_class.php';
require 'db_connect.php';
require 'user.php';

$_post = new post_class();
$user = new user();
$user->authorize($db_conn, $_post);
