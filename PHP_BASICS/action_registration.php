<?php
require 'post_class.php';
require 'db_class.php';
require 'logger_class.php';
require 'user.php';

$user = new user();
$user->start_session();
$user->register();

