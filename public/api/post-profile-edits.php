<?php

session_start();

require_once __DIR__ . '/../../app/UserQuery.php';

use Revine\UserQuery;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method Not Allowed: Only POST requests are allowed.";
    exit;
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Unauthorized: You must be logged in to edit your profile.";
    exit;
}

$bio = $_POST['bio'] ?? null;
$tagline = $_POST['tagline'] ?? null;
$userId = $_SESSION['user_id'];
$profilePicture = $_FILES['profile_picture']['tmp_name'] ?? null;

$userQuery = new UserQuery();

$updateResult = $userQuery->updateProfile($userId, $bio, $tagline);

if ($updateResult) {
    if ($profilePicture !== null) {
        $updatePictureResult = $userQuery->updateProfilePicture($userId, $profilePicture);
        if (!$updatePictureResult) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update profile picture']);
            exit;
        }
    }
    http_response_code(200);
    echo json_encode(['message' => 'Profile updated successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update profile']);
}
