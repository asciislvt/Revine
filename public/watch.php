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

$videoQuery = new VideoQuery();
$queryResult = $videoQuery->getVideoById($videoId);

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
    <link rel="stylesheet" href="css/common.css" />
    <title><?= $queryResult['title'] ?> | Revine</title>
  </head>
  <body>
    <h1><?= htmlspecialchars($queryResult['title']) ?></h1>
    <p><?= htmlspecialchars($queryResult['upload_date']) ?></p>
    <p>Category: <?= htmlspecialchars($queryResult['category_name']) ?></p>
    <p>Uploaded by: <?= htmlspecialchars($queryResult['username']) ?></p
    <p><?= htmlspecialchars($queryResult['description']) ?></p>
    <video controls width="720" height="905">
      <source src="<?= htmlspecialchars($queryResult['video_url']); ?>" type="video/mp4" />
      Your browser does not support the video tag.
    </video>
    <!-- <p id="likes-count">Likes: <?= $queryResult['likes_count'] ?></p> -->
    <button id="like-button" value="like">Like</button>
    <button id="dislike-button" value="dislike">Dislike</button>
    <div id="comments">
      <h2>Comments</h2>
      <form id="comment-form" data-video-id="<?= htmlspecialchars($videoId) ?>">
        <textarea name="comment" rows="4" cols="50"></textarea>
        <button id="submit-comment">Submit Comment</button>
        <p id="comment-error" style="color: red;"></p>
      </form>
      <div id="comments-list">
        <!-- Comments loaded here :D -->
      </div>
    </div>
    <script src="js/comments.js"></script>
    <script src="js/likes.js"></script>
  </body>
</html>
