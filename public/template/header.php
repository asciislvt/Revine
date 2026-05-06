<?php

if (!isset($_SESSION['user_id'])) {
    $isLoggedIn = false;
} else {
    $isLoggedIn = true;
}

?>

<header>
  <h1>Revine</h1>
  <nav>
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="explore.php">Explore</a></li>
      <?php if ($isLoggedIn) : ?>
      <li><a href="upload.php">Upload</a></li>
      <li><a href="profile.php?user=<?= urlencode($_SESSION['username']) ?>">Profile</a></li>
      <li><a href="api/logout.php">Logout</a></li>
      <?php else : ?>
      <li><a href="login.php">Login</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>