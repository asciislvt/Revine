<?php

session_start();

require_once __DIR__ . '/../../app/FollowQuery.php';
require_once __DIR__ . '/../../app/UserQuery.php';

use Revine\FollowQuery,

    Revine\UserQuery;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$followQuery = new FollowQuery();
$userQuery = new UserQuery();

$jsonInput = json_decode(file_get_contents('php://input'), true);

$userId = $_SESSION['user_id'];
$usernameToFollow = $jsonInput['username'] ?? null;
$userToFollow = $userQuery->getIdByUsername($usernameToFollow);

$isAlreadyFollowing = $followQuery->isFollowing($userId, $userToFollow['id']);
if ($isAlreadyFollowing) {
    $unfollowResult = $followQuery->unfollow($userId, $userToFollow['id']);
    if ($unfollowResult) {
        http_response_code(200);
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to unfollow user']);
    }
} else {
    $followResult = $followQuery->follow($userId, $userToFollow['id']);
    if ($followResult) {
        http_response_code(200);
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to follow user']);
    }
}
