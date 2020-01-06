<?php

class Servers implements Request {
  private $pdo;

  private $user_id;

  public function __construct($pdo, $data) {
    $this->pdo = $pdo;

    $this->user_id = isset( $_REQUEST['user_id'] ) ? (int) $_REQUEST['user_id'] : 0;

    $this->doRequest();
  }

  private function doRequest() {
    $data = array(
      ':user_id' => $this->user_id,
    );

    if (empty($data[':user_id'])) {
      $this->response(false, SERVERS_ERRORS[0]);
    }

    $dbh = $this->pdo->getDBH();

    $stmt = $dbh->prepare('SELECT * FROM servers WHERE user_id=:user_id', array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute($data);

    $data = array();

    $result = $stmt->fetchAll();

    if (count($result) == 0) {
      $result = null;
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
