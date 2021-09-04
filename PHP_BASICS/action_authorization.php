<?php

function get_POST_email ():string {
  return htmlentities($_POST['email'], ENT_QUOTES);
}
//ToDo: Password Hashing
function get_POST_password ():string {
  return password_hash($_POST['password'], PASSWORD_DEFAULT);
}
//
echo "User Email: " . get_POST_email() . "<br>" . "User password(HASH): " . get_POST_password() . "<br>";
