<?php
class db_class{
  public $conn, $err;
  private $username = "user";
  private $password = "password";
  private $database = "smile";
  private $servername = "localhost";

  function __construct() {
    try {
      $this->conn = new PDO("mysql:host=" . $this->servername . ";dbname=" . $this->database, $this->username, $this->password);
      // set the PDO error mode to exception
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //      $this->err = new logger_class();
      //      $this->err = set_error("Connected successfully");
    }
    catch
    (PDOException $e) {
      //      $this->err = set_error("<style> h6{text-align: left; color: firebrick}</style><h6>Connection failed: </h6>>" . $e->getMessage());
      //      echo "$this->err->get_errors_str()";
    }
  }
  function query ($str) {
    try {
    $stmt = $this->conn->prepare($str);
    $stmt->execute();
    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $stmt->fetchAll();
  } catch(PDOException $e) {
echo "Error: " . $e->getMessage();
}
    $this->conn = NULL;
  }


}