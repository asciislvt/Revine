<?php

require_once __DIR__ . '/../app/VideoQuery.php';
require_once __DIR__ . '/../app/UserQuery.php';

use Revine\VideoQuery,

    Revine\UserQuery;

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

$userQuery = new UserQuery();
$profileInfo = $userQuery->getProfileInfoByUsername($username);
$profileStats = $userQuery->getProfileStatsByUsername($username);

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
  <link rel="stylesheet" href="css/navigation-bar.css" />
  <link rel="stylesheet" href="css/watch.css" />
  <link rel="stylesheet" href="https://cdn.plyr.io/3.8.4/plyr.css" />
  <title><?= $queryResult['title'] ?> | Revine</title>
</head>

<body>
  <?php include 'template/header.php'; ?>
  <main>
    <div id="container">

      <div id="video-container">
        <div id="video-player">
          <video id="player" controls loop crossorigin playsinline
            poster="<?= "/videos/" . $queryResult['video_id'] . "/thumbnail.jpg" ?>">
            <source src="<?= htmlspecialchars($queryResult['video_url']); ?>" type="video/mp4" />
            Your browser does not support the video tag.
          </video>
          <div id="like-container">
            <button class="ui-button" id="like-button" value="like">
              <svg>
                <use href="images/assets/like.svg#like" />
              </svg>
            </button>
            <p id="like-count">0</p>
          </div>
        </div>

        <div id="video-info">
          <div id="video-title">
              <h1
                class="
                <?php if (strlen($queryResult['title']) > 20) : ?>
                  long-title
                <?php endif; ?>
                "
              ><?= htmlspecialchars($queryResult['title'], ENT_QUOTES) ?></h1>
            <div id="video-extras">
              <p><?= htmlspecialchars($queryResult['upload_date']) ?></p>
              <p><?= htmlspecialchars($queryResult['category_name']) ?></p>
            </div>
          </div>
          <p><?= $queryResult['description'] ?></p>
        </div>

      </div>

      <div id="social-container">

        <div id="uploader">
          <div id="uploader-header">
            <?php if ($isLoggedIn && $username !== $_SESSION['username']) : ?>
            <button class="ui-button" id="follow-button" value="follow" data-username="<?= $username ?>">
              <svg>
                <use href="images/assets/follow.svg#follow" />
              </svg>
            </button>
            <?php endif; ?>
            <a href="/profile.php?user=<?= $queryResult['username'] ?>">
              <?php if ($hasPfp) : ?>
              <img class="profile-picture" src="/users/<?= $username ?>/profile.jpg" alt="Profile Picture" />
              <?php else : ?>
              <img class="profile-picture" src="images/default-profile.jpg" alt="Default Profile Picture" />
              <?php endif; ?>
            </a>
            <div id="uploader-details">
              <div id="details-header">
                <a href="/profile.php?user=<?= $queryResult['username'] ?>">
                  <h3><?= htmlspecialchars($queryResult['username']) ?></h3>
                </a>
                <p><?= $profileInfo['tagline'] ?></p>
              </div>
              <div id="stats">
                <span><?= $profileStats['followers'] ?>
                  <svg class="stats-icon">
                    <title>Followers</title>
                    <use href="images/assets/followers.svg#followers" />
                  </svg>
                </span>
                <span><?= $profileStats['following'] ?>
                  <svg class="stats-icon" alt="Following">
                    <title>Following</title>
                    <use href="images/assets/following.svg#following" />
                  </svg>
                </span>
                <span><?= $profileStats['videos'] ?>
                  <svg class="stats-icon" alt="Videos">
                    <title>Videos</title>
                    <use href="images/assets/videos.svg#videos" />
                  </svg>
                </span>
              </div>
            </div>
            <p id="follow-error" style="color: red; display: none;"></p>
          </div>
          <hr />
        </div>

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

      </div>

    </div>
    <div id="comments">
    </div>
  </main>
  <script src="js/comments.js"></script>
  <script src="js/likes.js"></script>
  <script src="js/follow.js"></script>
  <script src="https://cdn.plyr.io/3.8.4/plyr.js"></script>
  <script>
  const player = new Plyr('#player', {
    controls: [
      'play-large',
      'current-time',
      'mute',
      'volume',
      'fullscreen',
      'download',
    ],
  });
  </script>
</body>

</html>
