<?php
require_once './backend.php';

$lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
$firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
$phoneNumber = filter_input(INPUT_POST, 'phoneNumber', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$verifyPassword = filter_input(INPUT_POST, 'verifyPassword', FILTER_SANITIZE_STRING);

// si tous les champs obligatoire sont remplis
if (strlen($lastname) > 0 && strlen($firstname) > 0 && strlen($phoneNumber) > 0 && strlen($email) > 0 && strlen($password) > 0 && strlen($verifyPassword) > 0) {
  if (IsTaken($email) == false) {
    if ($password == $verifyPassword) {
      if (Register($lastname, $firstname, $phoneNumber, $email, $password)) {
        echo json_encode([
          'ReturnCode' => 0,
          'Success' => 'Le compte a bien été crée.'
        ]);
        exit();
      } else {
        echo json_encode([
          'ReturnCode' => 1,
          'Error' => 'Erreur lors de la création du compte'
        ]);
        exit();
      }
    } else {
      echo json_encode([
        'ReturnCode' => 2,
        'Error' => 'Les deux mots de passe données ne sont pas les mêmes'
      ]);
      exit();
    }
  } else {
    echo json_encode([
      'ReturnCode' => 3,
      'Error' => 'Cette adresse mail est déjà utilisé'
    ]);
    exit();
  }
}
