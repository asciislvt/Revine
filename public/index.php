<?php

session_start();

$isLoggedIn = isset($_SESSION['user_id']);

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/common.css" />
    <link rel="stylesheet" href="css/index.css" />
    <title>Revine</title>
  </head>
  <body>
    <div id="container">
      <header>
        <h1>Revine</h1>
        <p>Hello, <?= ($isLoggedIn ? htmlspecialchars($_SESSION['username']) : "Stranger") ?>.</p>
        <?php if ($isLoggedIn) : ?>
        <p>Upload and share your videos with the world!</p>
        <?php else : ?>
        <p>Login to upload and share your videos with the world!</p>
        <?php endif; ?>
      </header>
      <nav>
        <ul>
          <li><a href="explore.php">Explore</a></li>
          <li><a href="upload.php">Upload</a></li>
          <?php if (!$isLoggedIn) : ?>
            <li><a href="login.php">Login</a></li>
          <?php else : ?>
            <li><a href="profile.php?user=<?= urlencode($_SESSION['username'] ?? '') ?>">Profile</a></li>
            <li><a href="api/logout.php">Logout</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </body>
</html>
