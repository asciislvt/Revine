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
  <main>
    <header>
        <h1>Revine</h1>
        <nav>
          <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="explore.php">Explore</a></li>
            <li><a href="api/logout.php">Logout</a></li>
          </ul>
        </nav>
    </header>
    <?php if ($isOwnProfile) : ?>
      <button id="edit-profile-button">Edit Profile</button>
      <form id="edit-profile-form" style="display: none;">
        <label>
          Bio:
          <textarea id="bio" name="bio" maxlength="256" rows="4" cols="50"><?= htmlspecialchars($profileInfo['bio']) ?></textarea>
        </label>
        <br />
        <label>
          Tagline:
          <input
            type="text"
            maxlength="128"
            id="tagline"
            name="tagline"
            value="<?= htmlspecialchars($profileInfo['tagline']) ?>"
          />
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
        <button type="submit" id="save-edit-button">Save Changes</button>
        <button type="button" id="cancel-edit-button">Cancel</button>
      </form>
    <?php endif; ?>
    <?php if (!$selectedUsername) : ?>
      <p>No user selected. Please provide a username in the URL.</p>
    <?php else : ?>
      <div id="container">
        <div id="profile-info">
        <p>Showing profile for user: <strong><?= htmlspecialchars($selectedUsername) ?></strong></p>
        <?php if ($hasPfp) : ?>
          <img id="profile-picture" src="/users/<?= $selectedUsername ?>/profile.jpg" alt="Profile Picture" width="150" height="150" />
        <?php else : ?>
        <img id="profile-picture" src="images/default-profile.jpg" alt="Profile Picture" width="150" height="150" />
        <?php endif; ?>
          <?php
            foreach ($profileInfo as $key => $value) {
                echo "<p><strong>" . htmlspecialchars($key) . ":</strong> " . htmlspecialchars($value) . "</p>";
            }
            ?>
        </div>
        <div id="video-grid" data-username="<?= htmlspecialchars($selectedUsername) ?>">
          <p id="status">Loading videos...</p>
        </div>
      </div>
    <?php endif; ?>
  </main>
  <script type="module" src="js/profile/profile.js"></script>
  <?php if ($isOwnProfile) : ?>
    <script type="module" src="js/profile/edit-profile.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
  <?php endif; ?>
</body>

</html>
