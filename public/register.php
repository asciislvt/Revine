<?php

session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/common.css" />
    <link rel="stylesheet" href="css/navigation-bar.css" />
    <link rel="stylesheet" href="css/login.css" />
    <title>Register | Revine</title>
  </head>
  <body>
    <?php include 'template/header.php'; ?>
    <div id="container">
      <h1>Register</h1>
      <p>Create an account to start sharing your videos with the world!</p>

      <form id="register-form" autocomplete="off" method="POST">
        <label>
          Email:
          <input type="email" name="email" placeholder="john@pork.com" />
        </label>
        <br />
        <label>
          Username:
          <input type="text" name="username" placeholder="John_Pork" />
        </label>
        <br />
        <label>
          Password:
          <input type="password" name="password" placeholder="Password" />
        </label>
        <div id="password-requirements" style="display: none;">
          <h4>Password Requirements:</h4>
          <ul>
            <li>At least 8 characters.</li>
            <li>At least one uppercase letter.</li>
            <li>At least one lowercase letter.</li>
            <li>At least one number.</li>
          </ul>
        </div>
        <br />
        <button id="submit-register">Register</button>
      </form>
      <p id="error-message" style="color:red;"></p>

      <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>

    <script src="js/register.js"></script>
  </body>
</html>
