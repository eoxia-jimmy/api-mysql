<?php

include('./pdo.php');

$pdo = new Custom_PDO();

$user_id = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : 0;

$dbh = $pdo->getDBH();


$data = array(
  ':user_id'    => $user_id,
);

$stmt = $dbh->prepare('SELECT * FROM servers WHERE user_id=:user_id', array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$stmt->execute($data);

$data = array();

$result = $stmt->fetchAll();

echo json_encode($result);
exit;
