<?php

include('./pdo.php');

$pdo = new Custom_PDO();

$user_id = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : 0;
$url = isset( $_REQUEST['url'] ) ? $_REQUEST['url'] : 0;
$token = isset( $_REQUEST['token'] ) ? $_REQUEST['token'] : 0;

$dbh = $pdo->getDBH();


$data = array(
  ':user_id'    => $user_id,
  ':name'       => 'No name',
  ':url'     => $url,
  ':url_image' => 'https://miro.medium.com/max/3840/1*8pyex4RuP4GBxEQec0B3Lg.png',
  ':token'      => $token,
);

$stmt = $dbh->prepare('INSERT INTO servers (user_id, name, url, url_image, token) VALUES(:user_id, :name, :url, :url_image, :token)');
$stmt->execute($data);

$data = array();

$result = array(
  'id' => $dbh->lastInsertId(),
  'name' => 'No name',
  'url' => $url,
  'url_image' => 'https://miro.medium.com/max/3840/1*8pyex4RuP4GBxEQec0B3Lg.png',
);

echo json_encode($result);
exit;
