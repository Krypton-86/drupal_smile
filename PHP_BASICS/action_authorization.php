<?php
class post_class {
  public $flag = TRUE;
  function getEmail(): string {
    $return_var = htmlentities($_POST['email'], ENT_QUOTES);
    $len=strlen($return_var);
   if ($len < 5 || $len > 255){
      $this->flag=FALSE;
    }
      return $return_var;
  }
  function getPassword(): string {
    $return_var = htmlentities($_POST['password'], ENT_QUOTES);
    $len=strlen($return_var);
    if ($len < 8 || $len > 32){
      $this->flag=FALSE;
    }
    return $return_var;
  }
  function getPasswordHash(): string {
    return password_hash($this->getPassword(), PASSWORD_DEFAULT);
  }
  function getFname(): string {
    $return_var = htmlentities($_POST['fname'], ENT_QUOTES);
    $len=strlen($return_var);
    if ($len < 3 || $len > 255){
      $this->flag=FALSE;
    }
    return $return_var;
  }
  function getLname(): string {
    $return_var = htmlentities($_POST['lname'], ENT_QUOTES);
    $len=strlen($return_var);
    if ($len < 3 || $len > 255){
      $this->flag=FALSE;
    }
    return $return_var;
  }
  function getBirthday(): string {
    $return_var = htmlentities($_POST['birthday'], ENT_QUOTES);
    $len=strlen($return_var);
    if ($len < 8 || $len > 10){
      $this->flag=FALSE;
    }
    return $return_var;
  }
  function getCategories(): string {
    $categories = NULL;
    foreach ($_POST['categories'] as $category){
    if ($categories===NULL){
      $categories = $category;
    }else
      $categories = $categories . ", " . htmlentities($category, ENT_QUOTES);
    }
    return $categories;
  }
  function getConfirmRegCheck(): bool {
    if(htmlentities($_POST['confirm_reg_check'], ENT_QUOTES)=="CHECKED"){
      return TRUE;
    } else {
      $this->flag = FALSE;
      return FALSE;
    }
  }
  function getRememberCheck(): bool {
    return htmlentities($_POST['remember_check'], ENT_QUOTES) == "REMEMBER" ? TRUE : FALSE;
  }
}
//
$_post = new post_class();
$hash=password_hash($_post->getPassword(), PASSWORD_DEFAULT);
echo "User Email: " . $_post->getEmail() . "<br>" . "User password: " . $_post->getPassword() . "<br>________________________<br>";
$hash2=password_hash($_post->getPassword(), PASSWORD_DEFAULT);
echo $hash . "<br>" . $is_correct = password_verify($_post->getPassword(), $hash)? "password is correct<br>":"password failed<br>";
echo $hash2 . "<br>" . $is_correct = password_verify($_post->getPassword(), $hash2)? "password is correct<br>":"password failed<br>";
$cat = $_post->getCategories();
echo $cat;
