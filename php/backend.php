<?php
require_once __DIR__ . './DatabaseController.php';

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
 * @date 05.05.2020
 * @brief Fonction qui log l'utilisateur
 * @param array les infos de login de l'utilisateur
 *
 * @return array|false
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

    if ( $result !== false > 0) {
      if (LoggedDate($result['idUsers'])) {
        return $result;
      } else {
        return false;
      }
    } else {
      return false;
    }

  } catch (PDOException $e) {
    return null;
  }
}

/**
 * @author Hoarau Nicolas
 *
 * @date 05.05.2020
 * @brief Fonction qui ajoute la derniere date de login
 * 
 * @param integer $idUser
 * @return boolean
 * @version 1.0.0
 */
function LoggedDate(int $idUser) : bool
{
  $query = <<<EX
  UPDATE users 
  SET lastLogin = :loggedDate 
  WHERE idUsers = :idUsers;
  EX;

  $date = date('Y-m-d');

  try {
    DatabaseController::beginTransaction();

    $req = DatabaseController::prepare($query);
    $req->bindParam(':loggedDate', $date);
    $req->bindParam(':idUsers', $idUser, PDO::PARAM_INT);
    $req->execute();
    DatabaseController::commit();
    return true;
  } catch (PDOException $e) {
    DatabaseController::rollBack();
    return false;
  }
}

/**
 * @author Hoatau Nicolas
 * 
 * @date 05.05.2020
 * @brief Fonction qui fait le register en sql
 * @param array $args
 * 
 * @return boolean
 * @version 1.0.0
 */
function Register(string $lastname, string $firstname, string $phoneNumber, string $email, string $password): bool
{
  $query = <<<EX
    INSERT INTO users (lastname, firstname, phoneNumber, email, password, salt, idRoles)
    VALUES (:lastname, :firstname, :phoneNumber, :email, :password, :salt, 1);
    EX;

  $salt = hash('sha256', microtime());
  $userPassword = hash('sha256', $password . $salt);

  try {
    DatabaseController::beginTransaction();

    $requestRegister = DatabaseController::prepare($query);
    $requestRegister->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $requestRegister->bindParam(':firstname', $firstname, PDO::PARAM_STR);
    $requestRegister->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR, 12);
    $requestRegister->bindParam(':email', $email, PDO::PARAM_STR);
    $requestRegister->bindParam(':password', $userPassword, PDO::PARAM_STR);
    $requestRegister->bindParam(':salt', $salt);

    $requestRegister->execute();

    DatabaseController::commit();
    return true;
  } catch (PDOException $e) {
    DatabaseController::rollBack();
    return false;
  }
}

/**
 * @author Hoarau Nicolas
 *
 * @date 05.05.2020
 * @brief Fonction qui vérifie si le paramètre donnée(email, nickname) est déjà utilisé ou non
 * @param array $args
 * 
 * @return boolean|null
 * @version 1.0.0
 */
function IsTaken(string $email): ?bool
{
  $query = <<<EX
    SELECT email
    FROM users
    WHERE email = :email
    EX;

  try {
    $requestIsUsed = DatabaseController::prepare($query);
    $requestIsUsed->bindParam(':email', $email, PDO::PARAM_STR);
    $requestIsUsed->execute();
    $result = $requestIsUsed->fetch(PDO::FETCH_ASSOC);

    return $result !== false ? true : false;
  } catch (PDOException $e) {
    return null;
  }
}
