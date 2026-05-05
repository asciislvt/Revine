<?php

require_once __DIR__ . '/../../app/FollowQuery.php';
require_once __DIR__ . '/../../app/UserQuery.php';

use Revine\FollowQuery,

    Revine\UserQuery;

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized, please log in!']);
    exit;
}

$userId = $_SESSION['user_id'];

$userQuery = new UserQuery();
$followQuery = new FollowQuery();
$targetUsername = $_GET['username'] ?? null;
$userToCheck = $userQuery->getIdByUsername($targetUsername);

$isFollowing = $followQuery->isFollowing($userId, $userToCheck['id']);
$followCount = $followQuery->getFollowerCount($targetUsername);

echo json_encode([
    'isFollowing' => $isFollowing,
    'followCount' => $followCount
]);
