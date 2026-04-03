<?php

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Async Upload Test</title>
    <style>
      #container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
      }
    </style>
  </head>
  <body>
    <div id="container">
      <h1>Async Upload Test</h1>

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
      </form>
    </div>

    <script src="js/upload.js"></script>
  </body>
</html>
