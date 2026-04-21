<?php

require_once __DIR__ . '/../../app/CommentQuery.php';

use Revine\CommentQuery;

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed. Please use GET.']);
    exit;
}

$videoId = $_GET['video_id'] ?? null;

$commentQuery = new CommentQuery();
$comments = $commentQuery->getCommentsByVideoId($videoId);

if ($comments === null) {
    http_response_code(404);
    echo json_encode(['error' => 'Video not found or no comments available.']);
    exit;
}

http_response_code(200);
echo json_encode($comments);
