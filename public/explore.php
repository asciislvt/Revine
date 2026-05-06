<?php

session_start();

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
    <label>
      Sort By:
      <select name="order-by" id="order-by">
        <option value="recent">Most Recent</option>
        <option value="trending">Trending</option>
      </select>
    </label>
    <div id="video-grid">
      <p id="status">Loading videos...</p>
    </div>
    <script type="module" src="js/explore.js"></script>
  </body>
</html>
