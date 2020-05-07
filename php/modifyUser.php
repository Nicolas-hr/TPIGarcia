<?php
require_once __DIR__ . './DatabaseController.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$oldEmail = filter_input(INPUT_POST, 'oldEmail', FILTER_SANITIZE_EMAIL);
$email = filter_input(INPUT_POST, 'userEmail', FILTER_SANITIZE_EMAIL);
$firstname = filter_input(INPUT_POST, 'userFirstname', FILTER_SANITIZE_STRING);
$lastname = filter_input(INPUT_POST, 'userLastname', FILTER_SANITIZE_STRING);
$phoneNumber = filter_input(INPUT_POST, 'userPhoneNumber', FILTER_SANITIZE_STRING);

$query = <<<EX
UPDATE users 
SET email = :email, firstname = :firstname, lastname = :lastname, phoneNumber = :phoneNumber
WHERE email = :oldEmail
EX;

if ($_SESSION['loggedUser']['idRoles'] == 2) {
  if (strlen($email) > 0 && strlen($firstname) > 0 && strlen($lastname) > 0 && strlen($phoneNumber) > 0) {
    try {
      DatabaseController::beginTransaction();

      $modifyUserRequest = DatabaseController::prepare($query);
      $modifyUserRequest->bindParam(':oldEmail', $oldEmail, PDO::PARAM_STR);
      $modifyUserRequest->bindParam(':email', $email, PDO::PARAM_STR);
      $modifyUserRequest->bindParam(':firstname', $firstname, PDO::PARAM_STR);
      $modifyUserRequest->bindParam(':lastname', $lastname, PDO::PARAM_STR);
      $modifyUserRequest->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
      $modifyUserRequest->execute();

      DatabaseController::commit();

      echo json_encode([
        'ReturnCode' => 0,
        'Success' => "L'utilisateur a bien été modifié"
      ]);
    } catch (PDOException $e) {
      DatabaseController::rollBack();
      echo json_encode([
        'ReturnCode' => 1,
        'Error' => $e->getMessage()
      ]);
    }
  } else {
    echo json_encode([
      'ReturnCode' => 2,
      'Error' => "Vous devez remplir tous les champs"
    ]);
  }
} else {
  echo json_encode([
    'ReturnCode' => 3,
    'Error' => "Vous n'avez pas les droits"
  ]);
}
