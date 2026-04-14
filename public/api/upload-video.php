<?php

session_start();

require_once __DIR__ . '/../../app/VideoValidator.php';
require_once __DIR__ . '/../../app/VideoUploader.php';

use Revine\VideoValidator, Revine\VideoUploader;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed. Please use POST.']);
    exit;
}

if ($_SESSION['user_id'] === null) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized. Please log in to upload videos.']);
    exit;
}

$uploadedVideo = $_FILES['video-file'] ?? null;

$isValidVideo = VideoValidator::validate($uploadedVideo);

if (!$isValidVideo) {
    http_response_code(400);
    echo json_encode(['error' => $isValidVideo]);
    exit;
} else {
    $videoTitle = $_POST['title'];
    $videoDescription = $_POST['description'];

    $uploaderResult = VideoUploader::upload($uploadedVideo, $videoTitle, $videoDescription, $_SESSION['user_id']);

    if ($uploaderResult['status'] !== 'success') {
        http_response_code(500);
        echo json_encode(['error' => $uploaderResult['message']]);
        exit;
    }

    http_response_code(200);
    echo json_encode([
        'message' => 'Video uploaded successfully.',
        'videoId' => $uploaderResult['videoId'],
    ]);
}
