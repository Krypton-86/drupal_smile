<?php
require 'post_class.php';
require 'db_connect.php';

  // Prepare query, check validity
  $_post_auth = new post_class();
  if ($_post_auth->getValidStatus()) {
    $db_query = "SELECT info.user_id, info.Email, hashes.hash_id, hashes.hash FROM users.info LEFT JOIN hashes ON info.user_id = hashes.hash_id WHERE Email='" . $_post_auth->getEmail() . "';";
//    $db_query = "SELECT user_id FROM info;";
    $result = mysqli_query($db_conn, $db_query);
    // Run query for writing user info
    if (mysqli_num_rows($result) > 0) {
     echo "ok";
      while($row = mysqli_fetch_assoc($result)) {
        echo "id: " . $row["user_id"] . " - Email: " . $row["Email"] . "<br>";
      }
    }
    else {
      echo "Error finding user info: " . $db_query . "<br>" . mysqli_error($db_conn);
    }
  } else {
    //Write validity errors
    echo "<br>" . $_post_auth->getErrors() . "<br>";
  }
  mysqli_close($db_conn);

//$hash=password_hash($_post->getPassword(), PASSWORD_DEFAULT);
//echo "User Email: " . $_post->getEmail() . "<br>" . "User password: " . $_post->getPassword() . "<br>________________________<br>";
//$hash2=password_hash($_post->getPassword(), PASSWORD_DEFAULT);
//echo $hash . "<br>" . $is_correct = password_verify($_post->getPassword(), $hash)? "password is correct<br>":"password failed<br>";
//echo $hash2 . "<br>" . $is_correct = password_verify($_post->getPassword(), $hash2)? "password is correct<br>":"password failed<br>";
//$cat = $_post->getCategories();
//$confirmReg = $_post->getConfirmRegCheck();
//$remember = $_post->getRememberCheck();
//$date = $_post->getBirthday();
//$lname = $_post->getLname();
//$fname = $_post->getFname();
//$valid = $_post->getValidStatus();
//$err = $_post->getErrors();


