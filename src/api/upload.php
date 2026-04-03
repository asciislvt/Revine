<?php

require_once '../validation/VideoValidator.php';
use Revine\VideoValidator;

$VALID_URL_CHARS = '/[^A-Za-z0-9_-]/iu';
$VIDEO_DIR = '/data/videos';

// We'll used this later to save the upload to server ;)
$uploaded_video = $_FILES['uploaded-video'] ?? null;

$is_valid = VideoValidator::isVideoValid($uploaded_video);

if (!$is_valid) {
    http_response_code(400);
    echo json_encode($is_valid);
    exit;
} else {
    $video_title = $_POST['video-title'] ?? '';
    $video_description = $_POST['video-description'] ?? '';
    $file_name = $uploaded_video['name'];
    $file_type = $uploaded_video['type'];
    $file_size = $uploaded_video['size'];
    $raw_hash = hash("xxh3", $file_name . time(), true);
    $hash_name = preg_replace($VALID_URL_CHARS, '', substr(base64_encode($raw_hash), 0, 6));
    $target_path = "$VIDEO_DIR/$hash_name";
    $target_file = "$target_path/src.mp4";
    $url = "http://localhost:8080/video.php?hash=$hash_name";

    if (!is_dir($target_path)) {
        if (!mkdir($target_path, 0775, true)) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create target directory!']);
            exit;
        }
    }

    $temp_path = $uploaded_video['tmp_name'];

    if (!move_uploaded_file($temp_path, $target_file)) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to move uploaded file!']);
        exit;
    }

    $data = [
      'video_title' => $video_title,
      'video_description' => $video_description,
      'file_name' => $file_name,
      'file_type' => $file_type,
      'file_size' => $file_size,
      'hash_name' => $hash_name,
      'target_file' => $target_file,
      'url' => $url
    ];

    header('Content-Type: application/json');
    echo json_encode($data);
}
