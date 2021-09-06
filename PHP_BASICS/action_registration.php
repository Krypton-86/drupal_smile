<?php
require 'post_class.php';
require 'db_connect.php';

// Prepare query, check validity
$_post_reg = new post_class();
if ($_post_reg->getValidStatus() && $_post_reg->getConfirmRegCheck()) {
  $db_query = "INSERT INTO smile.users (First_name, Last_name, Email, Birthday, Passwords)
  VALUES ('" . $_post_reg->getFname()
    . "', '" . $_post_reg->getLname()
    . "', '" . $_post_reg->getEmail()
    . "', '" . $_post_reg->getBirthday()
    . "', '" . $_post_reg->getPasswordHash()
    . "');";
  // Run query for writing user info
  if (mysqli_query($db_conn, $db_query)) {
    echo "New user info record created successfully<br>";
    echo "____________________________________<br><br>";
    echo "Dear " . $_post_reg->getFname() . ", <br>You can authorize with your email in this page: <br><a href='/authorization.html'>Log In</a>";

  }
  else {
    echo "Error writing user info: " . $db_query . "<br>" . mysqli_error($db_conn);
  }
}
else {
  //Write validity errors
  echo "<br>$_post_reg->getErrors()<br>";
}
mysqli_close($db_conn);


