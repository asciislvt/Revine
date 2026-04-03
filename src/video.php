<?php

$hash = $_GET['hash'] ?? '';
$url = "/videos/$hash/src.mp4";

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Video Page</title>
  </head>
  <body>
    <h1>Video Page</h1>
    <p>This is where the video will be displayed.</p>
    <?php if ($hash === '') : ?>
      <p>No video hash provided!</p>
    <?php else : ?>
      <p>Video hash provided: <?php echo htmlspecialchars($hash); ?></p>
      <video controls>
        <source src="<?= $url ?>" type="video/mp4">
        Your browser does not support the video tag.
      </video>
    <?php endif; ?>
  </body>
</html>
