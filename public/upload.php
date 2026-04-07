<?php

session_start();

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload A Video | Revine</title>
  </head>
  <body>
    <div id="container">
      <h1>Upload A Video</h1>

      <?php if (!isset($_SESSION['username'])) : ?>
        <p>You must be logged in to upload a video. <a href="login.php">Login here</a>.</p>
      <?php else : ?>
        <form id="upload-form" autocomplete="off" method="POST" enctype="multipart/form-data">
          <label>
            Video Title:
            <input type="text" name="video-title" placeholder="Video Title">
          </label>
          <label>
            Video Description:
            <textarea name="video-description" placeholder="Video Description"></textarea>
          </label>

          <br />

          <input type="hidden" name="MAX_FILE_SIZE" value="300000000">
          <input type="file" name="uploaded-video">
          <button id="submit-video">Upload</button>
          <p id="upload-url"></p>
        </form>
      <?php endif; ?>
    </div>

    <!-- <script src="js/upload.js"></script> -->
</html>
