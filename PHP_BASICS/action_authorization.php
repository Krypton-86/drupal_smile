<?php
require 'post_class.php';
require 'db_connect.php';

// Prepare query, check validity
$_post_auth = new post_class();
if ($_post_auth->getValidStatus()) {
  $db_query = "SELECT Password FROM smile.users WHERE Email='" . $_post_auth->getEmail() . "';";
  $result = mysqli_query($db_conn, $db_query);
  // Run query for writing user info
  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    if (password_verify($_post_auth->getPassword(), $row['Password'])) {
      echo '<br>Password is ok!<br>';
      header( "refresh:1;url=mypage.php" );
    }
    else {
      echo "<style> h1{text-align: center; color: firebrick}</style> <br><br><br><h1>Wrong password!</h1><br><br><br>";
      header( "refresh:3;url=authorization.html" );
    }
  }
  else {
    echo "Error finding user info: " . $db_query . "<br>" . mysqli_error($db_conn);
  }
}
else {
  //Write validity errors
  echo "<br>" . $_post_auth->getErrors() . "<br>";
}
mysqli_close($db_conn);

//$hash=password_hash($_post_auth->getPassword(), PASSWORD_DEFAULT);
//$hash2=password_hash($_post_auth->getPassword(), PASSWORD_DEFAULT);
//echo $hash . "<br>" . $is_correct = password_verify($_post_auth->getPassword(), $hash)? "password is correct<br>":"password failed<br>";
//echo $hash2 . "<br>" . $is_correct = password_verify($_post_auth->getPassword(), $hash2)? "password is correct<br>":"password failed<br>";
//$cat = $_post->getCategories();
//$confirmReg = $_post->getConfirmRegCheck();
//$remember = $_post->getRememberCheck();
//$date = $_post->getBirthday();
//$lname = $_post->getLname();
//$fname = $_post->getFname();
//$valid = $_post->getValidStatus();
//$err = $_post->getErrors();


