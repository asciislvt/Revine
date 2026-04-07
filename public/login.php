<?php

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Revine</title>
  </head>
  <body>
    <div id="container">
      <h1>Login</h1>

      <form id="login-form" autocomplete="off" method="POST">
        <label>
          Username:
          <input type="text" name="username" placeholder="Username">
        </label>
        <br />
        <label>
          Password:
          <input type="password" name="password" placeholder="Password">
        </label>
        <br />
        <button id="submit-login">Login</button>
      </form>

      <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>

    <!-- <script src="js/login.js"></script> -->
</html>
