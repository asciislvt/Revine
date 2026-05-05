<?php

require_once __DIR__ . '/../app/VideoQuery.php';

use Revine\VideoQuery;

session_start();

$videoId = $_GET['id'] ?? null;

if (!isset($_SESSION['user_id'])) {
    $isLoggedIn = false;
} else {
    $isLoggedIn = true;
}

if ($videoId === null) {
    http_response_code(400);
    echo "Bad Request: Missing video ID.";
    exit;
}

$videoQuery = new VideoQuery();
$queryResult = $videoQuery->getVideoById($videoId);

$username = $queryResult['username'] ?? null;
$hasPfp = is_file("/data/users/$username/profile.jpg");

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
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
      </ul>
    </nav>
    <h1><?= $queryResult['title'], ENT_QUOTES ?></h1>
    <span>
      <a href="/profile.php?user=<?= $queryResult['username'] ?>">
          <?php if ($hasPfp) : ?>
            <img
              src="/users/<?= $username ?>/profile.jpg"
              alt="Profile Picture"
              width="50"
              height="50"
            />
          <?php else : ?>
            <img 
              src="images/default-profile.jpg"
              alt="Default Profile Picture"
              width="50"
              height="50"
            />
          <?php endif; ?>
        <?= htmlspecialchars($queryResult['username']) ?>
      </a>
      <button id="follow-button" value="follow"  data-username="<?= $username ?>">
        Follow
      </button>
      <p id="follow-error" style="color: red; display: none;"></p>
    </span>
    <p><?= htmlspecialchars($queryResult['upload_date']) ?></p>
    <p>Category: <?= htmlspecialchars($queryResult['category_name']) ?></p>
    <p><?= $queryResult['description'] ?></p>
    <video controls muted="true" autoplay="true" loop="true" width="720" height="905">
      <source src="<?= htmlspecialchars($queryResult['video_url']); ?>" type="video/mp4" />
      Your browser does not support the video tag.
    </video>
    <button id="like-button" value="like">Like</button>
    <p id="like-count">0 Likes</p>
    <div id="comments">
      <h2>Comments</h2>
      <?php if ($isLoggedIn) : ?>
        <form id="comment-form" data-video-id="<?= htmlspecialchars($videoId) ?>">
          <textarea name="comment" rows="4" cols="50"></textarea>
          <button id="submit-comment">Submit Comment</button>
          <p id="comment-error" style="color: red;"></p>
        </form>
      <?php else : ?>
        <p>You must be logged in to post comments.</p>
      <?php endif; ?>
      <div id="comments-list">
        <!-- Comments loaded here :D -->
      </div>
    </div>
    <script src="js/comments.js"></script>
    <script src="js/likes.js"></script>
    <script src="js/follow.js"></script>
  </body>
</html>
