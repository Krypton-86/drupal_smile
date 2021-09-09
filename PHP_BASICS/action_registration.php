<?php
session_start();
if (array_key_exists('user_id', $_COOKIE)) {
  header("refresh:0;url=mypage.php");
}
require 'post_class.php';
require 'db_connect.php';

$_post_reg = new post_class();
$is_registered = TRUE;
// Check email is used
$db_query_email = "SELECT user_id FROM smile.users WHERE Email='" . $_post_reg->getEmail() . "';";
$result = mysqli_query($db_conn, $db_query_email);
if (mysqli_num_rows($result) > 0) {
  $is_registered = TRUE;
}
else {
  $is_registered = FALSE;
}
if ($_post_reg->getValidStatus() && $_post_reg->getConfirmRegCheck() && !$is_registered) {
  $db_query = "INSERT INTO smile.users (First_name, Last_name, Email, Birthday, Password" . $_post_reg->getCategoriesString() . ")
  VALUES ('" . $_post_reg->getFname()
    . "', '" . $_post_reg->getLname()
    . "', '" . $_post_reg->getEmail()
    . "', '" . $_post_reg->getBirthday()
    . "', '" . $_post_reg->getPasswordHash()
    . "'" . $_post_reg->getCategoriesTruesMap()
    . ");";
  // Run query for writing user info
  if (mysqli_query($db_conn, $db_query)) {
    $result = mysqli_query($db_conn, $db_query_email);
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      setcookie("user_id", $row['user_id'], time() + (86400 * 30), "/"); // 86400 = 1 day
      echo '<style> h1, h2{text-align: center; color: darkslategrey;}</style> <br><br><br><h1>New user info record created successfully!<h1><br>';
      echo "Dear " . $_post_reg->getFname() . ", <br><h2>Next time you can authorize with email in this page: <br><a href='/index.php'>Log In</a><h2>";
      header("refresh:5;url=mypage.php");
    }
  }
  else {
    echo "Error writing user info: " . $db_query . "<br>" . mysqli_error($db_conn);
  }
}
else {
  //Write validity errors
  if ($is_registered) {
    echo '<style> h1, h2{text-align: center; color: firebrick;}</style> <br><br><br><h1>This email registered!<h1><br>';
  }
  else {
    echo implode("<br>", $_post_reg->getErrors());
  }
  header("refresh:3;url=registration.html");
}
mysqli_close($db_conn);


