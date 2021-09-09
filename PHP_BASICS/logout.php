<?php
setcookie("PHPSESSID", "0", time() - (86400), "/"); // 86400 = 1 day
setcookie("user_id", "0", time() - (86400), "/"); // 86400 = 1 day
setcookie("remember", "0", time() - (86400), "/"); // 86400 = 1 day
session_unset();
session_destroy();

if ($_COOKIE['remember'] === "wrong password"){
echo "<style> h1{text-align: center; color: firebrick}</style> <br><br><br><h1>Wrong password!</h1><br><br><br>";
}
else {
  echo '<style> h1{text-align: center; color: darkslategrey;}</style> <br><br><br><h1>Logged out!<h1><br><br><br>';
}
header( "refresh:3;url=index.php" );