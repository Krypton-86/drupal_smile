<?php
require 'post_class.php';
$username = "user";
$password = "password";
$database = "users";
$servername = "localhost";
//

//Create connection

// Create connection
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
//echo "Connected successfully" . "<br>";
//if ($result = $mysqli->query("SELECT * FROM names;")) {
//  var_dump ($result);
//  var_dump($result ->fetch_all());
//}
//echo"h";