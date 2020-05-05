<?php
require_once __DIR__. './DatabaseController.php';

/**
 * @date 05.05.20
 * @author Hoarau Nicolas
 *
 * @brief Fonction qui vérifie si l'utilisateur est connecté
 * 
 * @return boolean
 * 
 * @version 1.0.0
 */
function isLogged(): bool
{
  return array_key_exists('loggedIn', $_SESSION) && $_SESSION['loggedIn'];
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ USER FUNCTIONS ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
/**
 * @author Hoarau Nicolas
 * 
 * @date 05.05.2020
 * @brief Fonction qui récupère le salt de l'utilisateur
 * @param array $args
 * 
 * @return string
 * @version 1.0.0
 */
function GetSalt(string $email): ?string
{
  $query = <<<EX
    SELECT salt
    FROM users
    WHERE email = :email;
    EX;

  try {
    $requestSalt = DatabaseController::prepare($query);
    $requestSalt->bindParam(':email', $email, PDO::PARAM_STR);
    $requestSalt->execute();

    $result = $requestSalt->fetch(PDO::FETCH_ASSOC);

    return $result !== false ? $result['salt'] : null;
  } catch (PDOException $e) {
    return null;
  }
}

/**
 * @author Hoarau Nicolas
 *
 * @date 22.03.2020
 * @brief Fonction qui log l'utilisateur
 * @param array les infos de login de l'utilisateur
 *
 * @return User|false
 * @version 1.0.0
 */
function Login(array $args)
{
  // initialise à null les colonnes du tableau vide/inexistante
  $args += [
    'userEmail' => null,
    'userPwd' => null,
  ];

  extract($args); // extrait les données du tableau avec comme nom de varariable son nom de colonne

  $loginField = "";
  $authenticator = "";
  $salt = "";

  if ($userEmail !== null) {
    $salt = GetSalt($userEmail);
    $authenticator = $userEmail;
    $loginField = "email";
  } else {
    return false;
  }

  if ($userPwd == null)
    return false;

  $pwd = hash('sha256', $userPwd . $salt);

  $query = <<<EX
    SELECT idUsers, email, firstname, lastname, email
     FROM users
     WHERE `{$loginField}` = :wayToConnectValue 
     AND password = :pwd;
    EX;

  try {
    $requestLogin = DatabaseController::prepare($query);
    $requestLogin->bindParam(':wayToConnectValue', $authenticator, PDO::PARAM_STR);
    $requestLogin->bindParam(':pwd', $pwd, PDO::PARAM_STR);
    $requestLogin->execute();

    $result = $requestLogin->fetch(PDO::FETCH_ASSOC);

    return $result !== false > 0 ?  $result: false;
  } catch (PDOException $e) {
    return null;
  }
}
