<?php

include('./pdo.php');

$pdo = new Custom_PDO();

$id = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : 0;

$dbh = $pdo->getDBH();


$data = array(
  ':id'    => $id,
);

$stmt = $dbh->prepare('DELETE FROM servers WHERE id=:id');
$stmt->execute($data);

$data = array();

$result = array(
  'status' => true,
);

echo json_encode($result);
exit;
