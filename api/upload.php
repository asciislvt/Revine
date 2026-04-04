<?php

require_once 'utils/VideoValidator.php';
require_once 'utils/DbConnection.php';

use Revine\VideoValidator,

    Revine\DbConnection;

$VALID_URL_CHARS = '/[^A-Za-z0-9_-]/iu';
$VIDEO_DIR = '/data/videos';

$uploaded_video = $_FILES['uploaded-video'] ?? null;

$is_valid = VideoValidator::isVideoValid($uploaded_video);

if (!$is_valid) {
    http_response_code(400);
    echo json_encode($is_valid);
    exit;
} else {
    $video_title = $_POST['video-title'] ?? '';
    $video_description = $_POST['video-description'] ?? '';

    // Generate a unique hash for the video using xxh3 and base64 encoding, then sanitize it to be URL-friendly
    $file_name = $uploaded_video['name'] ?? 'video';
    $raw_hash = hash("xxh3", $file_name . time(), true);
    $hash_name = preg_replace($VALID_URL_CHARS, '', substr(base64_encode($raw_hash), 0, 6));
    $url = "http://localhost:8080/video.php?hash=$hash_name";

    // Create the target directory for the video and move the uploaded file there
    $target_path = "$VIDEO_DIR/$hash_name";
    $target_file = "$target_path/src.mp4";

    if (!is_dir($target_path)) {
        if (!mkdir($target_path, 0775, true)) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create target directory!']);
            exit;
        }
    }

    // Move the upload to the target location
    $temp_path = $uploaded_video['tmp_name'];

    if (!move_uploaded_file($temp_path, $target_file)) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to move uploaded file!']);
        exit;
    }

    // Insert video metadata into the database
    $db = DbConnection::getInstance()->getConnection();

    $query = "INSERT INTO videos (title, description, video_id)
              VALUE (?, ?, ?)";

    $stmt = $db->prepare($query);
    $stmt->execute([
        $video_title,
        $video_description,
        $hash_name
    ]);

    // Return the video metadata and URL as JSON
    $data = [
      'video_title' => $video_title,
      'hash_name' => $hash_name,
      'url' => $url
    ];

    header('Content-Type: application/json');
    echo json_encode($data);
}
