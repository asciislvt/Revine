<?php

require_once __DIR__ . '/../api/utils/DbConnection.php';

use Revine\DbConnection;

// Get the video hash from the query parameters
$hash = $_GET['hash'] ?? '';
$url = "/videos/$hash/src.mp4";

// Fetch video metadata from the database
$db = DbConnection::getInstance()->getConnection();
$query = "SELECT * FROM videos WHERE video_id = ?";

$stmt = $db->prepare($query);
$stmt->execute([$hash]);

$meta = $stmt->fetch();

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?= $meta['title'] ?> - REVINE</title>
  </head>
  <body>
    <?php if ($hash === '') : ?>
      <p>No video hash provided!</p>
    <?php elseif (!$meta) : ?>
      <p>Video not found!</p>
    <?php else : ?>
      <video controls>
        <source src="<?= $url ?>" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <h3>Video Title: <?= htmlspecialchars($meta['title']) ?></h3>
      <p>Video Description: <?= htmlspecialchars($meta['description']) ?></p>
    <?php endif; ?>
  </body>
</html>
