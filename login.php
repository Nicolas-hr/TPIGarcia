<?php
require_once './php/backend.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (IsLogged() == true) {
  header('Location: ./login.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/sweetalert2.css">

  <title>Login</title>
</head>

<body>
  <?php require_once './includes/navbar.inc.php'; ?>

  <form method="POST">
    <div class="form-group">
      <label for="mail">Email</label>
      <input type="email" class="form-control" id="email">
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" class="form-control" id="password">
    </div>
    <button type="submit" class="btn btn-primary" id="btnLogUser">Login</button>
  </form>

  <?php require_once './includes/footer.inc.html'; ?>

  <script src="./js/login.js"></script>
</body>

</html>