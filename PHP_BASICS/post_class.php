<?php

class post_class {

  private $isValid = TRUE;

  private $errors = [];

  /**
   * Validates user entered Email from $_POST
   *
   * @return string
   */
  function getEmail(): string {
    $return_var = htmlentities($_POST['email'], ENT_QUOTES);
    $len = strlen($return_var);
    if ($len < 5 || $len > 255 || !filter_var($return_var, FILTER_VALIDATE_EMAIL)) {
      $this->isValid = FALSE;
      array_push($this->errors, "Email: Enter a valid email.");
    }
    return $return_var;
  }

  /**
   * Generates pass hash from $this->getPassword()
   *
   * @return string
   */
  function getPasswordHash(): string {
    return password_hash($this->getPassword(), PASSWORD_DEFAULT);
  }

  /**
   * Validates user entered Password from $_POST
   *
   * @return string
   */
  function getPassword(): string {
    $return_var = htmlentities($_POST['password'], ENT_QUOTES);
    if (!array_key_exists('user_id', $_COOKIE)) {
      if (htmlentities($_POST['password2'], ENT_QUOTES) == $return_var) {
        $len = strlen($return_var);
        if ($len < 8 || $len > 32) {
          $this->isValid = FALSE;
          array_push($this->errors, "Password: Use passphrase from 8 to 32 symbols.");
        }
      }
      else {
        $this->isValid = FALSE;
        array_push($this->errors, "Passwords not match.");
      }
    }
    else {
      if ($return_var == "" || $_POST['password2'] == "") {
        return $return_var;
      }
      else {
        if (htmlentities($_POST['password2'], ENT_QUOTES) == $return_var) {
          $len = strlen($return_var);
          if ($len < 8 || $len > 32) {
            $this->isValid = FALSE;
            array_push($this->errors, "Password: Use passphrase from 8 to 32 symbols.");
          }
        }
        else {
          $this->isValid = FALSE;
          array_push($this->errors, "Passwords not match.");
        }
      }
    }
    return $return_var;
  }

  /**
   * Validates user entered First name from $_POST
   *
   * @return string
   */
  function getFname(): string {
    $return_var = $_POST['fname'];
    $len = strlen($return_var);
    if ($len < 2 || $len > 255 || !preg_match("/^[a-zA-Z-' ]*$/", $return_var)) {
      $this->isValid = FALSE;
      array_push($this->errors, "First name: Only letters and white space allowed, name must be longer than one symbol.");
    }
    return htmlentities($return_var, ENT_QUOTES);
  }

  /**
   * Validates user entered Last name from $_POST
   *
   * @return string
   */
  function getLname(): string {
    $return_var = $_POST['lname'];
    $len = strlen($return_var);
    if ($len < 2 || $len > 255 || !preg_match("/^[a-zA-Z-' ]*$/", $return_var)) {
      $this->isValid = FALSE;
      array_push($this->errors, "Last name: Only letters and white space allowed, name must be longer than one symbol.");
    }
    return htmlentities($return_var, ENT_QUOTES);
  }

  /**
   * Validates user entered date from $_POST
   *
   * @return string
   */
  function getBirthday(): string {
    $return_var = htmlentities($_POST['birthday'], ENT_QUOTES);
    $len = strlen($return_var);
    if ($len < 8 || $len > 10 || !preg_match("/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/", $return_var)) {
      $this->isValid = FALSE;
      array_push($this->errors, "Birthdate: Check correct date.");
    }
    return $return_var;
  }

  /**
   * Creates "trues map" of categories (category_name1=true,
   * category_name2=false...) by selected in form items
   *
   * @return string
   */
  function getCategoriesTruesMap(): string {
    return $this->getCategoriesString() == "" ? "" : str_repeat(", true", count($this->getCategories()));
  }

  /**
   * Transforms array of Categories to string
   *
   * @return string
   */
  function getCategoriesString(): string {
    $categories = ucwords(implode(", ", $this->getCategories()));
    return $categories == "" ? "" : ", " . $categories;
  }

  /**
   * Validates user entered Categories from $_POST
   *
   * @return array
   */
  function getCategories(): array {
    $i = 0;
    $categories = [];
    foreach ($_POST['categories'] as $category) {
      $categories[$i] = htmlentities($category, ENT_QUOTES);
      $i++;
    }
    return $categories;
  }

  /**
   * Checks "confirm registration" checkbox is selected
   *
   * @return bool
   */
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

  /**
   * Checks "remember me" checkbox is selected
   *
   * @return bool
   */
  function getRememberCheck(): bool {
    return htmlentities($_POST['remember_check'], ENT_QUOTES) == "REMEMBER";
  }

  /**
   * Returns status of validity user entered information in $_POST
   *
   * @return bool
   */
  function getValidStatus(): bool {
    return $this->isValid;
  }

  /**
   * Returns array of validity errors
   *
   * @return array
   */
  function getErrors(): array {
    return $this->errors;
  }

}