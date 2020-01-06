<?php

include_once('load.php');

class API implements Request {
  private $mPDO;

  private $mAction;

  public function __construct() {
    $this->mPDO = new Custom_PDO();

    $this->mAction = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
    unset($_REQUEST['action']);

    $headers = getallheaders();
    $wpapikey = isset( $headers['wpapikey'] ) ? $headers['wpapikey'] : '';

    if (in_array($this->mAction, ROUTES, true)) {
      switch($this->mAction) {
        case 'login':
          $login = new Login($this->mPDO, $_REQUEST);
          break;
        case 'subscribe':
          $subscribe = new Subscribe($this->mPDO, $_REQUEST);
          break;
        case 'servers':
          $servers = new Servers($this->mPDO, $_REQUEST);
          break;
        case 'join':
          $join = new Join($this->mPDO, $_REQUEST);
          break;
        case 'leave':
          $leave = new Leave($this->mPDO, $_REQUEST);
          break;
      }
    } else {
      $this->response(false, API_ERRORS[0]);
    }
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

new API();
