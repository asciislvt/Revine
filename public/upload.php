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
    <title>Upload A Video | Revine</title>
  </head>
  <body>
    <div id="container">
      <h1>Upload A Video</h1>
      
      <nav>
        <ul>
          <li><a href="index.php">Home</a></li>
        </ul>
      </nav>

      <?php if (!$isLoggedIn) : ?>
        <p>You must be logged in to upload a video. <a href="login.php">Login here</a>.</p>
      <?php else : ?>
        <form id="upload-form" autocomplete="off" method="POST" enctype="multipart/form-data">
          <label>
            Video Title:
            <input type="text" name="title" placeholder="Video Title">
          </label>
          <label>
            Video Description:
            <textarea name="description" placeholder="Video Description"></textarea>
          </label>
          <br />
          <label>
            Video Category:
          <select name="category">
            <option value="1">Music</option>
            <option value="1">Comedy</option>
            <option value="1">Education</option>
            <option value="1">Gaming</option>
          </select>
          </label>
          <br />
          <label>
            Upload Your Video:
            <input type="hidden" name="MAX_FILE_SIZE" value="300000000">
            <input type="file" name="video-file" accept="video/*">
            <p>Video must be under 300mb in size.</p>
          </label>
          <button id="submit-video">Upload</button>
          <p id="success-message"><a id="video-url"></a></p>
        </form>
        <p id="error-message" style="color: red;"></p>
      <?php endif; ?>
    </div>
    <script src="js/upload.js"></script>
  </body>
</html>
