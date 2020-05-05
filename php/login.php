<?php
require_once './backend.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Initialisation
$authenticator = filter_input(INPUT_POST, "authenticator", FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
$userLogged = null;

if (strlen($authenticator) > 0 && strlen($password) > 0) {
  if (strpos($authenticator, '@')) {
    $userLogged = Login(['userEmail' => $authenticator, 'userPwd' => $password]);
  } else {
    $userLogged = Login(['userNickname' => $authenticator, 'userPwd' => $password]);
  }

  if ($userLogged !== false) {
    $_SESSION['loggedUser'] = $userLogged;
    $_SESSION['loggedIn'] = true;

    echo json_encode([
      'ReturnCode' => 0,
      'Succes' => "Connexion effectué"
    ]);
    exit();
  }

  echo json_encode([
    'ReturnCode' => 1,
    'Error' => "Pseudo/Mot de passe invalide"
  ]);
  exit();
}
