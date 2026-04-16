<?php

require_once __DIR__ . '/../app/VideoQuery.php';

use Revine\VideoQuery;

session_start();

$videoId = $_GET['id'] ?? null;

if ($videoId === null) {
    http_response_code(400);
    echo "Bad Request: Missing video ID.";
    exit;
}

$queryResult = VideoQuery::getVideoById($videoId);

if ($queryResult === null) {
    http_response_code(404);
    echo "Video not found.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $queryResult['title'] ?> | Revine</title>
  </head>
  <body>
    <h1><?= htmlspecialchars($queryResult['title']) ?></h1>
    <p><?= htmlspecialchars($queryResult['uploade_date']) ?></p>
    <p>Uploaded by: <?= htmlspecialchars($queryResult['username']) ?></p
    <p><?= htmlspecialchars($queryResult['description']) ?></p>
    <video controls width="720" height="905">
      <source src="<?= htmlspecialchars($queryResult['video_url']); ?>" type="video/mp4" />
      Your browser does not support the video tag.
    </video>
    <p>Testing out thumbnail generation: </p>
    <img src="<?= htmlspecialchars($queryResult['thumbnail_url']); ?>" alt="Video Thumbnail" />
  </body>
</html>
