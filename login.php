<?php

class Login implements Request {
  private $pdo;

  private $email;
  private $password;

  public function __construct($pdo, $data) {
    $this->pdo = $pdo;

    $this->email = isset($data['email']) ? filter_var($data['email'], FILTER_SANITIZE_STRING) : null;
    $this->password = isset($data['password']) ? filter_var($data['password'], FILTER_SANITIZE_STRING) : null;

    $this->doRequest();
  }

  private function doRequest() {
    $data = array(
      ':email' => $this->email,
      ':password' => $this->password
    );

    if (empty($data[':email']) || empty($data[':password'])) {
      $this->response(false, LOGIN_ERRORS[0]);
    }

    $dbh = $this->pdo->getDBH();

    $stmt = $dbh->prepare('SELECT id FROM users WHERE email=:email AND password=:password', array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute($data);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
      $this->response(false, LOGIN_ERRORS[1]);
    }

    $this->response(true, null, $result);
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
