<?php

class Leave implements Request {
  private $pdo;

  private $id;

  public function __construct($pdo, $data) {
    $this->pdo = $pdo;

    $this->id = isset($data['id']) ? filter_var($data['id'], FILTER_SANITIZE_STRING) : null;

    $this->doRequest();
  }

  private function doRequest() {
    $data = array(
      ':id' => $this->id,
    );

    if (empty($this->id)) {
      $this->response(false, LEAVE_ERRORS[0]);
    }

    $dbh = $this->pdo->getDBH();

    $stmt = $dbh->prepare('DELETE FROM servers WHERE id=:id');
    $stmt->execute($data);

    $this->response(true, null, null);
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
