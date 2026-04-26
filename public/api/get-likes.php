<?php

session_start();

use Revine\UserQuery,

    Revine\LikesQuery;

require_once __DIR__ . '/../../app/LikesQuery.php';

$likesQuery = new LikesQuery();

$hasUserLiked = false;

if (isset($_SESSION['user_id'])) {
    $hasUserLiked = $likesQuery->hasUserLiked($_SESSION['user_id'], $_GET['videoId']);
}

$likeCount = $likesQuery->getLikesCount($_GET['videoId']);

if ($likeCount === null) {
    $likeCount = 0;
}

echo json_encode([
    'hasLiked' => $hasUserLiked,
    'likeCount' => $likeCount
]);
