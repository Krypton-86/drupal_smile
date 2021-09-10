<?php

class user {

  public $db;
  function __construct() {
    $this->db = new db_class();
  }

  /**
   * Implements user registration
   */
  function register() {
    $is_registered = TRUE;
    $_post = new post_class();
    // Check email is used
    $str = "SELECT user_id FROM smile.users WHERE Email='" . $_post->getEmail() . "';";
    $result = $this->db->query($str);

    if (count($result) > 0) {
      $is_registered = TRUE;
    }
    else {
      $is_registered = FALSE;
    }
    if ($_post->getValidStatus() && $_post->getConfirmRegCheck() && !$is_registered) {
      $db_query = "INSERT INTO smile.users (First_name, Last_name, Email, Birthday, Password" . $_post->getCategoriesString() . ")
  VALUES ('" . $_post->getFname()
        . "', '" . $_post->getLname()
        . "', '" . $_post->getEmail()
        . "', '" . $_post->getBirthday()
        . "', '" . $_post->getPasswordHash()
        . "'" . $_post->getCategoriesTruesMap()
        . ");";
      // Run query for writing user info
      $this->db->query($db_query);
      $result = $this->db->query($str);
        if (count($result) > 0) {
          setcookie("user_id", $result['0']['user_id'], time() + (86400 * 30), "/"); // 86400 = 1 day
          echo '<style> h1, h2{text-align: center; color: darkslategrey;}</style> <br><br><br><h1>New user info record created successfully!<h1><br>';
          echo "Dear " . $_post->getFname() . ", <br><h2>Next time you can authorize with email in this page: <br><a href='/index.php'>Log In</a><h2>";
          header("refresh:5;url=mypage.php");
        }
      }
    else {
      //Write validity errors
      if ($is_registered) {
        echo '<style> h1, h2{text-align: center; color: firebrick;}</style> <br><br><br><h1>This email registered!<h1><br>';
      }
      else {
        echo implode("<br>", $_post->getErrors());
      }
      header("refresh:3;url=registration.html");
    }
  }

  /**
   * Implements user authorization
   *
   * @param $db_conn
   * @param $_post_auth
   */
  function authorize($db_conn) {
    if ($_post->getValidStatus()) {
      $db_query = "SELECT user_id, Password FROM smile.users WHERE Email='" . $_post->getEmail() . "';";
      $result = mysqli_query($db_conn, $db_query);
      // Run query for writing user info
      if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($_post->getPassword(), $row['Password'])) {
          if ($_post->getRememberCheck()) {
            //set cookies
            setcookie("remember", "yes", time() + (86400 * 30), "/"); // 86400 = 1 day
          }
          else {
            setcookie("remember", "no", time() + (86400 * 30), "/"); // 86400 = 1 day
          }
          setcookie("user_id", $row['user_id'], time() + (86400 * 30), "/"); // 86400 = 1 day
          echo '<style> h1{text-align: center; color: darkslategrey;}</style> <br><br><br><h1>Everything will be OK!<h1><br><br><br>';
          header("refresh:1;url=mypage.php");
        }
        else {
          setcookie("remember", "wrong password", time() + (30), "/"); // 86400 = 1 day
          header("refresh:0;url=logout.php");
        }
      }
      else {
        echo "Error finding user info: " . $db_query . "<br>" . mysqli_error($db_conn);
      }
    }
    else {
      //Write validity errors
      echo "<br>" . $_post->getErrors() . "<br>";
    }
    $conn = NULL;
  }

  function edit($db_conn) {
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
    foreach ($categories as $key => $value) {
      if ($value) {
        $categories_str = $categories_str . ", $key=true";
      }
      else {
        $categories_str = $categories_str . ", $key=false";
      }
    }
    $pass = $_post->getPassword() == "" ? "'" : "', Password='" . $_post->getPasswordHash() . "'";
    $db_query = "UPDATE smile.users SET First_name='" . $_post->getFname()
      . "', Last_name='" . $_post->getLname()
      . "', Email='" . $_post->getEmail()
      . "', Birthday='" . $_post->getBirthday()
      . $pass
      . $categories_str . " WHERE user_id=" . $_COOKIE['user_id'] . ";";

    if ($_post->getValidStatus() && $_post->getConfirmRegCheck()) {

      // Run query for writing user info
      if (mysqli_query($db_conn, $db_query)) {
        echo '<style> h1{text-align: center; color: darkslategrey;}</style> <br><br><br><h1>User info edited successfully!<h1><br><br><br>';
        header("refresh:1;url=mypage.php");
      }
      else {
        echo "Error writing user info: " . $db_query . "<br>" . mysqli_error($db_conn);
      }
    }
    else {
      //Write validity errors
      echo implode("<br>", $_post->getErrors());
    }
    $conn = NULL;
  }

  function info($db_conn) {
    $db_query = "SELECT * FROM smile.users WHERE user_id='" . $_COOKIE['user_id'] . "';";
    $result = mysqli_query($db_conn, $db_query);
    // Run query for writing user info
    $from_db = [];
    if (mysqli_num_rows($result) > 0) {
      $from_db = mysqli_fetch_assoc($result);

    }
    else {
      return FALSE;
      echo '<style> h1{text-align: center; color: darkred;}</style> <br><br><br><br><br><br><br><br><br><h1>403<h1><br><br><br><br><br><br><br><br><br>';
      header("refresh:0;url=index.php");
    }
    return $from_db;
    $conn = NULL;
  }

}