<?php

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
} else {
    session_start();
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width", initial-scale="1.0" />
    <title>Register | Revine</title>
  </head>
  <body>
    <div id="container">
      <h1>Register</h1>

      <form id="register-form" autocomplete="off" method="POST">
        <label>
          Username:
          <input type="text" name="username" placeholder="Username" />
        </label>
        <br />
        <label>
          Password:
          <input type="password" name="password" placeholder="Password" />
        </label>
        <br />
        <button id="submit-register">Register</button>
      </form>

      <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>

    <!-- <script src="js/register.js"></script> -->
</html>
