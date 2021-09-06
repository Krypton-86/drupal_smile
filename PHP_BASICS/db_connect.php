<?php
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
}
else {
  echo "Connected successfully<br>";
}