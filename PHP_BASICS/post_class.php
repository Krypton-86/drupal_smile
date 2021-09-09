<?php

class post_class {

  private $isValid = TRUE;

  private $errors = [];

  function getEmail(): string {
    $return_var = htmlentities($_POST['email'], ENT_QUOTES);
    $len = strlen($return_var);
    if ($len < 5 || $len > 255 || !filter_var($return_var, FILTER_VALIDATE_EMAIL)) {
      $this->isValid = FALSE;
      array_push($this->errors, "Email: Enter a valid email.");
    }
    return $return_var;
  }

  function getPassword(): string {
      $return_var = htmlentities($_POST['password'], ENT_QUOTES);
      $len = strlen($return_var);
      if (!array_key_exists('user_id', $_COOKIE)) {
        if ($len < 8 || $len > 32) {
          $this->isValid = FALSE;
          array_push($this->errors, "Password: Use passphrase from 8 to 32 symbols.");
        }
      }
      return $return_var;
  }

  function getPasswordHash(): string {
    return password_hash($this->getPassword(), PASSWORD_DEFAULT);
  }

  function getFname(): string {
    $return_var = $_POST['fname'];
    $len = strlen($return_var);
    if ($len < 2 || $len > 255 || !preg_match("/^[a-zA-Z-' ]*$/", $return_var)) {
      $this->isValid = FALSE;
      array_push($this->errors, "First name: Only letters and white space allowed, must be longer than one symbol.");
    }
    return htmlentities($return_var, ENT_QUOTES);
  }

  function getLname(): string {
    $return_var = $_POST['lname'];
    $len = strlen($return_var);
    if ($len < 2 || $len > 255 || !preg_match("/^[a-zA-Z-' ]*$/", $return_var)) {
      $this->isValid = FALSE;
      array_push($this->errors, "Last name: Only letters and white space allowed, must be longer than one symbol.");
    }
    return htmlentities($return_var, ENT_QUOTES);
  }

  function getBirthday(): string {
    $return_var = htmlentities($_POST['birthday'], ENT_QUOTES);
    $len = strlen($return_var);
    if ($len < 8 || $len > 10 || !preg_match("/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/", $return_var)) {
      $this->isValid = FALSE;
      array_push($this->errors, "Birthdate: Check date.");
    }
    return $return_var;
  }

  function getCategories(): array {
    $i = 0;
    $categories = [];
    foreach ($_POST['categories'] as $category) {
      $categories[$i] = htmlentities($category, ENT_QUOTES);
      $i++;
    }
    return $categories;
  }

  function getCategoriesString(): string {
    $categories = ucwords(implode(", ", $this->getCategories()));
    return $categories == "" ? "" : ", " . $categories;
  }

  function getCategoriesTruesMap(): string {
    return $this->getCategoriesString() == "" ? "" : str_repeat(", true", count($this->getCategories()));
  }

  function getConfirmRegCheck(): bool {
    if (htmlentities($_POST['confirm_reg_check'], ENT_QUOTES) == "CHECKED") {
      return TRUE;
    }
    else {
      $this->isValid = FALSE;
      array_push($this->errors, "Confirm registration: User not confirmed registration!");
      return FALSE;
    }
  }

  function getRememberCheck(): bool {
    return htmlentities($_POST['remember_check'], ENT_QUOTES) == "REMEMBER";
  }

  function getValidStatus(): bool {
    return $this->isValid;
  }

  function getErrors(): array {
    return $this->errors;
  }

}