<?php

class user {

  private $db;

  /**
   * Creates new DB connection
   */
  function __construct() {
    $this->db = new db_class();
  }

  /**
   * Implements user registration
   */
  function register() {
    $_post = new post_class();
    $is_registered = TRUE;
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
   */
  function authorize() {
    $_post = new post_class();
    if ($_post->getValidStatus()) {
      $db_query = "SELECT user_id, Password FROM smile.users WHERE Email='" . $_post->getEmail() . "';";
      // Run query for writing user info
      $result = $this->db->query($db_query);
      if (count($result) > 0) {
        if (password_verify($_post->getPassword(), $result['0']['Password'])) {
          if ($_post->getRememberCheck()) {
            //set cookies
            setcookie("remember", "yes", time() + (86400 * 30), "/"); // 86400 = 1 day
          }
          else {
            setcookie("remember", "no", time() + (86400 * 30), "/"); // 86400 = 1 day
          }
          setcookie("user_id", $result['0']['user_id'], time() + (30), "/"); // 86400 = 1 day
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
  }

  /**
   * Implements possibility to edit user data in DB
   */
  function edit() {
    $_post = new post_class();
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
      foreach ($_post->getCategories() as $value2) {
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
      $this->db->query($db_query);
      header("refresh:0;url=mypage.php");

    }
    else {
      //Write validity errors
      echo implode("<br>", $_post->getErrors());
    }
  }

  /**
   * Returns array of user info from DB
   * @return array|false|void
   */
  function info() {
    $db_query = "SELECT * FROM smile.users WHERE user_id='" . $_COOKIE['user_id'] . "';";

    // Run query for writing user info
    $result = $this->db->query($db_query);
    if (!count($result) > 0) {
      return FALSE;
      echo '<style> h1{text-align: center; color: darkred;}</style> <br><br><br><br><br><br><br><br><br><h1>403<h1><br><br><br><br><br><br><br><br><br>';
      header("refresh:2;url=index.php");
    }
    else {
      return $result;
    }
  }

  /**
   * Starts PHP session and checks is user authorized before
   */
  function start_session(){
    session_start();
    if(array_key_exists('user_id', $_COOKIE)){
      header( "refresh:0;url=mypage.php" );
    }
  }

  /**
   * Ends PHP session and destroys cookies
   */
  function end_session(){
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
  }

}