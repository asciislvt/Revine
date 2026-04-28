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

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/common.css" />
  <?php if ($selectedUsername) : ?>
    <title><?= htmlspecialchars($selectedUsername) ?> | Revine</title>
  <?php else : ?>
    <title>Profile | Revine</title>
  <?php endif; ?>
</head>

<body>
  <nav>
    <ul>
      <li><a href="index.php">Home</a></li>
    </ul>
  </nav>
  <main>
    <h1>Profile</h1>
    <?php if ($isOwnProfile) : ?>
      <p>This is your profile. You can edit your information and manage your videos here.</p>
      <button id="edit-profile-button">Edit Profile</button>
      <form id="edit-profile-form" style="display: none;">
        <label>
          Bio:
          <textarea id="bio" name="bio" maxlength="256" rows="4" cols="50"></textarea>
        </label>
        <br />
        <label>
          Tagline:
          <input type="text" maxlength="128" id="tagline" name="tagline" />
        </label>
        <br />
        <label>
          Profile Picture:
          <input type="hidden" name="MAX_FILE_SIZE" value="300000000" />
          <input type="file" id="profile-picture-input" name="profile-picture" accept="image/png, image/jpeg" />
          <img id="profile-picture-preview" src="" alt=" " width="150" height="150" />
        </label>
        <br />
        <button type="submit" id="save-edit-button">Save Changes</button>
        <button type="button" id="cancel-edit-button">Cancel</button>
      </form>
    <?php endif; ?>
    <?php if (!$selectedUsername) : ?>
      <p>No user selected. Please provide a username in the URL.</p>
    <?php else : ?>
      <p>Showing profile for user: <strong><?= htmlspecialchars($selectedUsername) ?></strong></p>
      <img id="profile-picture" src="images/default-profile.jpg" alt="Profile Picture" width="150" height="150" />
        <?php
        $profileInfo = $userQuery->getProfileInfoByUsername($selectedUsername);
        foreach ($profileInfo as $key => $value) {
            echo "<p><strong>" . htmlspecialchars($key) . ":</strong> " . htmlspecialchars($value) . "</p>";
        }
        ?>
      <div id="video-grid" data-username="<?= htmlspecialchars($selectedUsername) ?>">
        <p id="status">Loading videos...</p>
      </div>
    <?php endif; ?>
  </main>
  <script type="module" src="js/profile/profile.js"></script>
  <?php if ($isOwnProfile) : ?>
    <script type="module" src="js/profile/edit-profile.js"></script>
  <?php endif; ?>
</body>

</html>
