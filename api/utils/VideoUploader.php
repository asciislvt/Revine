<?php

require_once 'utils/VideoValidator.php';
require_once 'utils/DbConnection.php';

use Revine\VideoValidator,

    Revine\DbConnection;

namespace Revine;

use Exception;

class VideoUploader
{
    public static function uploadVideo($uploaded_video, $video_title, $video_description)
    {
        $VALID_URL_CHARS = '/[^A-Za-z0-9_-]/iu';
        $VIDEO_DIR = '/data/videos';
        $TEMP_DIR = '/data/tmp';

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
                throw new Exception('Failed to create target directory!');
            }
        }

        // Move the upload to the target location
        $temp_path = $uploaded_video['tmp_name'];

        if (!move_uploaded_file($temp_path, $target_file)) {
            throw new Exception('Failed to move uploaded file!');
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

        // Return the video metadata and URL as an associative array
        return [
          'video_title' => $video_title,
          'hash_name' => $hash_name,
          'url' => $url
        ];
    }
}
