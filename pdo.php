<?php

class Custom_PDO {
  private $dbh;

  public function __construct() {
      $this->dbh = new PDO('mysql:host=' . DATABASE_HOST . ';dbname=' . DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
  }

  public function getDBH() {
    return $this->dbh;
  }
}
