<?php

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/common.css" />
    <link rel="stylesheet" href="css/explore.css" />
    <title>Explore | Revine</title>
  </head>
  <body>
    <h1>Explore</h1>
    <p>Discover trending videos and creators on Revine.</p>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="upload.php">Upload</a></li>
      </ul>
    </nav>
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
