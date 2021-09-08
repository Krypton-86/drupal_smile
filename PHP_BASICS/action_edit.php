<?php
require 'post_class.php';
require 'db_connect.php';

// Prepare query, check validity
$_post_reg = new post_class();
//UPDATE smile.users SET Cars=False, Books=False, Travel=False, Music=False, Sport=False, IT=False, Movies=False, Games=False, Relax=False, News=False WHERE user_id=2;
$categories = [
  "Cars" => FALSE,
  "Books" => FALSE,
  "Travel" => FALSE,
  "Music" => FALSE,
  "Sport" => FALSE,
  "IT" => FALSE,
  "Movies" => FALSE,
  "Games" => FALSE,
  "Relax" => FALSE,
  "News" => FALSE,
];
foreach ($categories as $key => $value1) {
  foreach ($_post_reg->getCategories() as $value2) {
    if ($key == $value2) {
      $categories[$key] = TRUE;
    }
  }
}
$categories_str = "";
foreach ($categories as $key => $value){
  if ($value){
    $categories_str = $categories_str . ", $key=true";
}
  else {
    $categories_str = $categories_str . ", $key=false";
  }
}
$pass = $_post_reg->getPassword()==""?"'":"', Password='" . $_post_reg->getPasswordHash() . "'";
$db_query = "UPDATE smile.users SET First_name='" . $_post_reg->getFname()
  . "', Last_name='" . $_post_reg->getLname()
  . "', Email='" . $_post_reg->getEmail()
  . "', Birthday='" . $_post_reg->getBirthday()
  . $pass
  . $categories_str . " WHERE user_id=" . $_COOKIE['user_id'] . ";";

if ($_post_reg->getValidStatus() && $_post_reg->getConfirmRegCheck()) {

  // Run query for writing user info
  if (mysqli_query($db_conn, $db_query)) {
    echo '<style> h1{text-align: center; color: darkslategrey;}</style> <br><br><br><h1>User info edited successfully!<h1><br><br><br>';
    header( "refresh:1;url=mypage.php" );
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