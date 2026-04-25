<?php

session_start();

require_once __DIR__ . '/../../app/LikesQuery.php';

use Revine\LikesQuery;

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized access, please log in."]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method Not Allowed, only POST requests are accepted."]);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

$videoId = $data['videoId'] ?? null;
$isLike = $data['isLike'] ?? null;
$userId = $_SESSION['user_id'];

if ($videoId === null || $isLike === null) {
    http_response_code(400);
    echo json_encode(["error" => "Bad Request: Missing video_id or is_like parameter."]);
    exit();
} else {
    $likesQuery = new LikesQuery();
    $result = $likesQuery->addLike($userId, $videoId, $isLike);
    if ($result) {
        http_response_code(200);
        echo json_encode(["message" => "Like status updated successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to update like status. Please try again later."]);
    }
}
