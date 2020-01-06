<?php

class Subscribe implements Request {
  private $pdo;

  private $email;
  private $username;
  private $password;

  public function __construct($pdo, $data) {
    $this->pdo = $pdo;

    $this->email = isset($data['email']) ? filter_var($data['email'], FILTER_SANITIZE_STRING) : null;
    $this->username = isset($data['username']) ? filter_var($data['username'], FILTER_SANITIZE_STRING) : null;
    $this->password = isset($data['password']) ? filter_var($data['password'], FILTER_SANITIZE_STRING) : null;

    if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      $this->response(false, SUBSCRIBE_ERRORS[1]);
    }

    $this->doRequest();
  }

  private function doRequest() {
    $data = array(
      ':email'    => $this->email,
      ':username' => $this->username,
      ':password' => $this->password,
    );

    if (empty($data[':email']) || empty($data[':username']) || empty($data[':password'])) {
      $this->response(false, SUBSCRIBE_ERRORS[0]);
    }

    $dbh = $this->pdo->getDBH();
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $dbh->prepare('INSERT INTO users (username, email, password) VALUES(:username, :email, :password)');

    try {
      $stmt = $stmt->execute($data);
    } catch (PDOException $e) {
      $this->response(false, (int) $e->getCode());
    }

    if (!$stmt) {
      $this->response(false, null, $dbh->errorInfo());
    }

    $this->response(true, null, $dbh->lastInsertId());
  }

  public function response($status, $error, $data = null) {
    echo json_encode(array(
      'status' => $status,
      'error' => $error,
      'data' => $data,
    ));
    exit;
  }
}
