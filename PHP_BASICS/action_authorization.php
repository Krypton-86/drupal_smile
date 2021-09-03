<?php

function giveEmail ():string {
  return htmlspecialchars($_POST['email']);
}
//ToDo: Password Hashing
function givePassword ():string {
  return htmlspecialchars($_POST['password']);
}

echo "User Email: " . giveEmail();
echo "<br>";
echo "User password: " . givePassword();