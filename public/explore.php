<?php

session_start();

$isLoggedIn = isset($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/common.css" />
  <link rel="stylesheet" href="css/navigation-bar.css" />
  <link rel="stylesheet" href="css/explore.css" />
  <title>Explore | Revine</title>
</head>

<body>
  <?php include 'template/header.php'; ?>
  <main>
    <div id="container">
      <div id="explore-options">
        <button class="explore-button" id="recent-button">Recent</button>
        <?php if ($isLoggedIn) : ?>
        <button class="explore-button" id="following-button">Following</button>
        <?php endif; ?>
        <button class="explore-button" id="trending-button" class="active">Trending</button>
      </div>

      <div id="video-grid">
        <p id="status">Loading videos...</p>
      </div>
    </div>
  </main>
  <script type="module" src="js/explore.js"></script>
</body>

</html>