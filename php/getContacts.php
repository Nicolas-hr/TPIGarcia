<?php
require_once './backend.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$query = <<<EX
SELECT idUsers, email, firstname, lastname, email, phoneNumber
FROM users
EX;

try {
  $requestContacts = DatabaseController::prepare($query);

  $requestContacts->execute();
  $result['contactsData'] = $requestContacts->fetchAll(PDO::FETCH_ASSOC);
  $result['userData']['idRoles'] = $_SESSION['loggedUser']['idRoles'];
  $result['userData']['idUsers'] = $_SESSION['loggedUser']['idUsers'];

  echo json_encode($result !== false > 0 ? $result : false);
} catch (PDOException $e) {
  echo json_encode($e->getMessage());
}