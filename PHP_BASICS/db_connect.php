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
  die("<style> h6{text-align: left; color: firebrick}</style><h6>Connection failed: </h6>>" . mysqli_connect_error());
}
else {
//  echo "<style> h6{text-align: left;}</style><h6>Connected successfully</h6><br>";
}