<?php

session_start();

require_once __DIR__ . '/../../app/CommentQuery.php';

use Revine\CommentQuery;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed. Please use POST.']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized. Please log in to post comments.']);
    exit;
}

$commentText = $_POST['comment'] ?? null;
$videoId = $_POST['video_id'] ?? null;

$commentQuery = new CommentQuery();
$addCommentResult = $commentQuery->addComment($videoId, $_SESSION['user_id'], $commentText);

if ($addCommentResult) {
    http_response_code(200);
    echo json_encode(['message' => 'Comment added successfully.']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to add comment. Please try again later.']);
}
