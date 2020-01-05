<?php

include('./pdo.php');

class Login {
    public function __construct() {

    }
}

$pdo = new Custom_PDO();

$username = isset( $_REQUEST['username'] ) ? $_REQUEST['username'] : '';
$password = isset( $_REQUEST['password'] ) ? $_REQUEST['password'] : '';

$dbh = $pdo->getDBH();


$data = array(
  ':email'    => $username,
  ':password' => $password
);

$stmt = $dbh->prepare('SELECT id FROM users WHERE email=:email AND password=:password', array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$stmt->execute($data);

$result = $stmt->fetch();

echo json_encode($result);
exit;
