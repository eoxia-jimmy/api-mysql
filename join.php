<?php

include('./pdo.php');

$pdo = new Custom_PDO();

$user_id = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : 0;
$url = isset( $_REQUEST['url'] ) ? $_REQUEST['url'] : 0;
$token = isset( $_REQUEST['token'] ) ? $_REQUEST['token'] : 0;

$dbh = $pdo->getDBH();

$url_info = $url . 'wp-json/task_manager/v1/get-info';

$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, $url_info);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, false);

// execute and return string (this should be an empty string '')
$str = curl_exec($curl);

curl_close($curl);

$data_curl = json_decode( $str, true );

$data_sql = array(
  ':user_id'    => $user_id,
  ':name'       => isset( $data_curl['name'] ) && ! empty( $data_curl['name'] ) ? $data_curl['name'] : 'Sans nom',
  ':url'     => $url,
  ':url_image' => isset( $data_curl['url_icon'] ) && ! empty( $data_curl['url_icon'] ) ? $data_curl['url_icon'] : 'https://miro.medium.com/max/3840/1*8pyex4RuP4GBxEQec0B3Lg.png',
  ':token'      => $token,
);

$stmt = $dbh->prepare('INSERT INTO servers (user_id, name, url, url_image, token) VALUES(:user_id, :name, :url, :url_image, :token)');
$stmt->execute($data_sql);

$data = array();

$result = array(
  'id' => $dbh->lastInsertId(),
  'name' => $data_sql[':name'],
  'url' => $url,
  'url_image' => $data_sql[':url_image']
);

echo json_encode($result);
exit;
