<?php

include('./pdo.php');

$pdo = new Custom_PDO();

$email = isset( $_REQUEST['email'] ) ? $_REQUEST['email'] : 0;
$username = isset( $_REQUEST['username'] ) ? $_REQUEST['username'] : 0;
$password = isset( $_REQUEST['password'] ) ? $_REQUEST['password'] : 0;

$dbh = $pdo->getDBH();


$data = array(
  ':email'    => $email,
  ':username' => $username,
  ':password' => $password,
);

$stmt = $dbh->prepare('INSERT INTO users (username, email, password) VALUES(:username, :email, :password)');
$stmt->execute($data);

$data = array();

echo json_encode($dbh->lastInsertId());
exit;
