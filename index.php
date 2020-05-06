<?php
require_once __DIR__ . '/php/backend.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if(IsLogged() == false) {
  header('Location: ./login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <title>Home</title>
</head>
<body>
  <?php require_once './includes/navbar.inc.php'; ?>
  <div id="contacts"></div>

  <?php require_once './includes/footer.inc.html'; ?>
  <script src="./js/contacts.js"></script>
</body>
</html>