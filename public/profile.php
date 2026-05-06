<?php

require_once __DIR__ . '/../app/UserQuery.php';

session_start();

$selectedUsername = $_GET['user'] ?? null;

use Revine\UserQuery;

$userQuery = new UserQuery();

if (isset($_SESSION['username']) && $_SESSION['username'] === $selectedUsername) {
    $isOwnProfile = true;
} else {
    $isOwnProfile = false;
}

$profileInfo = $userQuery->getProfileInfoByUsername($selectedUsername);
$profileStats = $userQuery->getProfileStatsByUsername($selectedUsername);
$hasPfp = is_file("/data/users/$selectedUsername/profile.jpg");

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/common.css" />
  <link rel="stylesheet" href="css/profile.css" />
  <link rel="stylesheet" href="css/navigation-bar.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
  <?php if ($selectedUsername) : ?>
  <title><?= htmlspecialchars($selectedUsername) ?> | Revine</title>
  <?php else : ?>
  <title>Profile | Revine</title>
  <?php endif; ?>
</head>

<body>
  <?php include 'template/header.php'; ?>
  <main>
    <?php if ($isOwnProfile) : ?>
    <div id="edit-profile-container">
      <h2>Edit Profile</h2>
      <form id="edit-profile-form">
        <label>
          Bio
          <textarea id="bio" name="bio" maxlength="256" rows="4"
            cols="50"><?= htmlspecialchars($profileInfo['bio']) ?></textarea>
        </label>
        <br />
        <label>
          Tagline
          <input type="text" maxlength="128" id="tagline" name="tagline"
            value="<?= htmlspecialchars($profileInfo['tagline']) ?>" />
        </label>
        <br />
        <label>
          Profile Picture:
          <input type="hidden" name="MAX_FILE_SIZE" value="300000000" />
          <input type="file" id="profile-picture-input" name="profile-picture" accept="image/png, image/jpeg" />
        </label>
        <div id="img-cropper" style="display: none;"></div>
        <button type="button" class="img-cropper-result" style="display: none;">Confirm</button>
        <br />
        <button type="submit" id="save-edit-button">
          <svg>
            <use href="images/assets/save.svg#save" />
          </svg>
        </button>
        <button type="button" id="cancel-edit-button">
          <svg>
            <use href="images/assets/cancel.svg#cancel" />
          </svg>
        </button>
      </form>
    </div>
    <?php endif; ?>
    <?php if (!$selectedUsername) : ?>
    <p>No user selected. Please provide a username in the URL.</p>
    <?php else : ?>
    <div id="container">
      <div id="profile" class="slide-in-bottom">
        <div id="profile-header">
          <div id="header-button-container">
            <?php if ($isOwnProfile) : ?>
            <button class="header-buttons" id="edit-profile-button">
              <svg>
                <use href="images/assets/edit.svg#edit" />
              </svg>
            </button>
            <?php else : ?>
            <button class="header-buttons" id="follow-button" data-username="<?= $selectedUsername ?>">
              <svg>
                <use href="images/assets/follow.svg#follow" />
              </svg>
            </button>
            <?php endif; ?>
          </div>
          <?php if ($hasPfp) : ?>
          <img id="profile-picture" src="/users/<?= $selectedUsername ?>/profile.jpg" alt="Profile Picture" width="150"
            height="150" />
          <?php else : ?>
          <img id="profile-picture" src="images/default-profile.jpg" alt="Profile Picture" width="150" height="150" />
          <?php endif; ?>
        </div>
        <div id="profile-info">
          <h2><?= htmlspecialchars($selectedUsername) ?></h2>
          <h3><?= htmlspecialchars($profileInfo['tagline']) ?></h3>
          <p><?= nl2br(htmlspecialchars($profileInfo['bio'])) ?></p>
          <div id="profile-stats">
            <div class="stat">
              <p class="stat-label">Followers</p>
              <p class="stat-number" id="followers-count"><?= $profileStats['followers'] ?></p>
            </div>
            <div class="stat">
              <p class="stat-label">Following</p>
              <p class="stat-number" id="following-count"><?= $profileStats['following'] ?></p>
            </div>
            <div class="stat">
              <p class="stat-label">Videos</p>
              <p class="stat-number" id="videos-count"><?= $profileStats['videos'] ?></p>
            </div>
          </div>
        </div>
      </div>
      <div id="videos" class="slide-in-bottom">
        <h2>Videos</h2>
        <div id="video-grid" data-username="<?= htmlspecialchars($selectedUsername) ?>">
          <p id="status">Loading videos...</p>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </main>
  <script type="module" src="js/profile/profile.js"></script>
  <?php if ($isOwnProfile) : ?>
  <script type="module" src="js/profile/edit-profile.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
  <?php else : ?>
  <script src="js/follow.js"></script>
  <?php endif; ?>
</body>

</html>