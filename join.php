<?php

class Join implements Request {
  private $pdo;

  private $wpapikey;

  private $user_id;
  private $url;
  private $token;

  public function __construct($pdo, $data, $wpapikey) {
    $this->pdo = $pdo;
    $this->wpapikey = $wpapikey;

    $this->user_id = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : 0;
    $this->url = isset( $_REQUEST['url'] ) ? $_REQUEST['url'] : 0;
    $this->token = isset( $_REQUEST['token'] ) ? $_REQUEST['token'] : 0;

    $this->doRequest();
  }

  private function checkAlreadyAdded() {
    $dbh = $this->pdo->getDBH();

    $data = array(
      ':url' => $this->url,
      ':user_id' => $this->user_id,
    );

    $stmt = $dbh->prepare('SELECT id FROM servers WHERE url=:url AND user_id=:user_id');
    $stmt->execute($data);
    $result = $stmt->fetch();

    return ! $result ? $result : true;
  }

  private function checkToken() {
    return true;
  }

  private function getInfo() {
    $url_info = $this->url . '/wp-json/task_manager/v1/get-info';

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url_info);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array( 'wpapikey: ' . $this->wpapikey ) );
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);

    $str = curl_exec($curl);

    curl_close($curl);

    if (!$str) {
      return false;
    }

    $data_curl = json_decode( $str, true );

    if (isset( $data_curl['code'] ) && $data_curl['code'] == 'rest_forbidden') {
      $this->response(false, JOIN_ERRORS[4]);
    }

    return $data_curl;
  }

  private function doRequest() {
    if (empty($this->user_id) || empty($this->token) || empty($this->url)) {
      $this->response(false, JOIN_ERRORS[0]);
    }

    if ($this->checkAlreadyAdded()) {
      $this->response(false, JOIN_ERRORS[1]);
    }

    $token_info = $this->checkToken();

    if (!$token_info) {
      $this->response(false, JOIN_ERRORS[2]);
    }

    $blog_info = $this->getInfo();

    if (!$blog_info) {
      $this->response(false, JOIN_ERRORS[3]);
    }

    $dbh = $this->pdo->getDBH();

    $data = array(
      ':user_id' => $this->user_id,
      ':name' => $blog_info['name'],
      ':url' => $this->url,
      ':url_image' => $blog_info['url_icon'],
      ':token' => $this->token,
      ':wp_user_id' => $blog_info['user_id']
    );

    $stmt = $dbh->prepare('INSERT INTO servers (user_id, name, url, url_image, token, wp_user_id) VALUES(:user_id, :name, :url, :url_image, :token, :wp_user_id)');
    $stmt->execute($data);

    $result = array(
      'id' => $dbh->lastInsertId(),
      'name' => $blog_info['name'],
      'url' => $this->url,
      'url_image' => $blog_info['url_icon'],
      'wp_user_id' => $blog_info['user_id'],
    );

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
