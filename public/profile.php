<?php

require_once __DIR__ . '/../app/UserQuery.php';

session_start();

$selectedUsername = $_GET['user'] ?? null;

use Revine\UserQuery;

$userQuery = new UserQuery();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <?php if ($selectedUsername): ?>
    <title><?= htmlspecialchars($selectedUsername) ?> | Revine</title>
  <?php else: ?>
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
    <?php if (!$selectedUsername): ?>
      <p>No user selected. Please provide a username in the URL.</p>
    <?php else: ?>
      <p>Showing profile for user: <strong><?= htmlspecialchars($selectedUsername) ?></strong></p>
      <?php
      $profileInfo = $userQuery->getProfileInfoByUsername($selectedUsername);
      foreach ($profileInfo as $key => $value) {
        echo "<p><strong>" . htmlspecialchars($key) . ":</strong> " . htmlspecialchars($value) . "</p>";
      }
      ?>
    <?php endif; ?>
    <div id="video-grid" data-username="<?= htmlspecialchars($selectedUsername) ?>">
      <p id="status">Loading videos...</p>
    </div>
  </main>
  <script type="module" src="js/profile.js"></script>
</body>

</html>