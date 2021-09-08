<?php
require 'post_class.php';
require 'db_connect.php';

// Prepare query, check validity
$_post_reg = new post_class();
if ($_post_reg->getValidStatus() && $_post_reg->getConfirmRegCheck()) {
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
    echo '<style> h1, h2{text-align: center; color: darkslategrey;}</style> <br><br><br><h1>New user info record created successfully!<h1><br>';
    echo "Dear " . $_post_reg->getFname() . ", <br><h2>Next time you can authorize with email in this page: <br><a href='/authorization.html'>Log In</a><h2>";
    header( "refresh:5;url=mypage.php" );
  }
  else {
    echo "Error writing user info: " . $db_query . "<br>" . mysqli_error($db_conn);
  }
}
else {
  //Write validity errors
  echo implode("<br>", $_post_reg->getErrors());
}
mysqli_close($db_conn);


