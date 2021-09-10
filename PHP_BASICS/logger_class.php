<?php

class logger_class {

  private $errors = [];

  public function __construct() {
  }

  public function set_error($str) {
    array_push($this->errors, $str);
  }

  public function get_errors_arr(): array {
    return $this->errors;
  }

  public function get_errors_str(): string {
    return implode(", ", $this->errors);
  }

}