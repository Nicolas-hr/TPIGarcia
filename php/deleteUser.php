<?php
require_once __DIR__ . './DatabaseController.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$email = filter_input(INPUT_POST, 'userEmail', FILTER_SANITIZE_EMAIL);

$query = <<<EX
DELETE FROM users
WHERE email = :email;
EX;

if ($_SESSION['loggedUser']['idRoles'] == 2) {
  if (strlen($email) > 0) {
    try {
      DatabaseController::beginTransaction();

      $deleteUserRequest = DatabaseController::prepare($query);
      $deleteUserRequest->bindParam(':email', $email, PDO::PARAM_STR);
      $deleteUserRequest->execute();

      DatabaseController::commit();
      
      echo json_encode([
        'ReturnCode' => 0,
        'Success' => "L'utlisateur a bien été supprimé"
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
      'Error' => "L'utilisateur sélectionné n'existe pas/plus"
    ]);
  }
} else {
  echo json_encode([
    'ReturnCode' => 3,
    'Error' => "Vous n'avez pas les droits"
  ]);
}
