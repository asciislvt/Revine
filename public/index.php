<?php

session_start();

$isLoggedIn = isset($_SESSION['user_id']);

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revine</title>
  </head>
  <body>
    <div id="container">
      <header>
        <h1>Revine</h1>
        <p>Hello, <?= ($isLoggedIn ? htmlspecialchars($_SESSION['username']) : "Stranger") ?>.</p>
        <p>Upload and share your videos with the world!</p>
      </header>
      <nav>
        <ul>
          <li><a href="explore.php">Explore</a></li>
          <li><a href="upload.php">Upload</a></li>
          <?php if (!$isLoggedIn) : ?>
            <li><a href="login.php">Login</a></li>
          <?php else : ?>
            <li><a href="api/logout.php">Logout</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </body>
</html>
