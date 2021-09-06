<?php
require 'post_class.php';
// Sensitive information:
$username = "user";
$password = "password";
$database = "users";
$servername = "localhost";
// Create connection
$db_conn = mysqli_connect($servername, $username, $password);
// Check connection
if (!$db_conn) {
  die("Connection failed: " . mysqli_connect_error());
} else {
  echo "Connected successfully<br>";
  // Prepare query, check validity
  $_post_auth = new post_class();
  if ($_post_auth->getValidStatus()) {
    $db_query = "SELECT users.info.user_id, users.info.Email, users.hashes.hash_id, users.hashes.hash FROM users.info LEFT JOIN users.hashes ON users.info.user_id=users.hashes.hash_id WHERE users.info.Email = '" . $_post_auth->getEmail() . "';";
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
}
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


