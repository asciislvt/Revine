<?php

namespace Revine;

use Revine\VideoTranscoder;

class VideoUploader
{
    private $TEMP_DIR;
    private $UPLOAD_DIR;
    private $VALID_URL_CHARS = '/[^A-Za-z0-9_-]/iu';

    public function __construct($tempDir = '/data/tmp/', $uploadDir = '/data/videos/')
    {
        $this->TEMP_DIR = $tempDir;
        $this->UPLOAD_DIR = $uploadDir;
    }

    public function upload($videoFile, $title, $description, $userId)
    {
        require_once __DIR__ . '/VideoTranscoder.php';

        if (empty($videoFile) || $videoFile['error'] !== UPLOAD_ERR_OK) {
            return [
              "status" => "error",
              "message" => "No video file uploaded or there was an upload error.",
            ];
        }

        if (empty($title) || empty($description)) {
            return [
              "status" => "error",
              "message" => "Video title and description are required.",
            ];
        }

        if (!$userId) {
            return [
              "status" => "error",
              "message" => "User ID is required for video upload.",
            ];
        }

        // Create unique ID for video
        $videoId = self::generateUniqueId($videoFile['name']);

        // Create temp and final directories and file paths
        $fileExtenstion = pathinfo($videoFile['name'], PATHINFO_EXTENSION);

        $tempDir = $this->TEMP_DIR . $videoId;
        $tempFilePath = $tempDir . '/' . $videoId . '_src.' . $fileExtenstion;
        $tempDirResult = self::createDirectory($tempDir);

        if ($tempDirResult !== true) {
            return $tempDirResult;
        }

        $finalDir = $this->UPLOAD_DIR . $videoId;
        $finalFilePath = $finalDir . '/' . $videoId . '.' . $fileExtenstion;
        $finalDirResult = self::createDirectory($finalDir);
        if ($finalDirResult !== true) {
            return $finalDirResult;
        }

        if (!move_uploaded_file($videoFile['tmp_name'], $tempFilePath)) {
            return [
              "status" => "error",
              "message" => "Failed to move uploaded file to temporary location.",
            ];
        }

        http_response_code(202);

        // TRANSCODE VIDEO
        $videoTranscoder = new VideoTranscoder();
        $transcodeResult = $videoTranscoder->transcodeVideo($tempFilePath, $finalFilePath);
        $thumbnailResult = $videoTranscoder->generateThumbnail($finalFilePath, $finalDir . '/thumbnail.jpg');

        if ($transcodeResult !== true) {
            self::cleanupTempFiles($tempFilePath, $tempDir);
            self::cleanupFinalFiles($finalFilePath, $finalDir);
            return $transcodeResult;
        } elseif ($thumbnailResult !== true) {
            self::cleanupTempFiles($tempFilePath, $tempDir);
            self::cleanupFinalFiles($finalFilePath, $finalDir);
            return $thumbnailResult;
        }

        $cleanup = self::cleanupTempFiles($tempFilePath, $tempDir);
        if ($cleanup !== true) {
            return $cleanup;
        }


        // Aight we done, throw that bitch in the database.
        $dbInsertResult = self::databaseInsert($videoId, $title, $description, $userId);

        if ($dbInsertResult !== true) {
            return $dbInsertResult;
        }

        return [
          "status" => "success",
          "message" => "Video uploaded and transcoded successfully.",
          "videoId" => $videoId,
        ];
    }

    private function databaseInsert($videoId, $title, $description, $userId)
    {
        require_once __DIR__ . '/DbConnection.php';

        $sanitizedTitle = self::sanatizeTitle($title);
        $sanitizedDescription = self::sanatizeDescription($description);
        $sanitizedUserId = intval($userId);

        $db = DbConnection::getInstance()->getConnection();
        $stmt = $db->prepare(
            "INSERT INTO videos (title, description, video_id, uploaded_by) 
             VALUES (:title, :description, :video_id, :uploaded_by)"
        );

        $stmt->bindParam(':title', $sanitizedTitle, \PDO::PARAM_STR);
        $stmt->bindParam(':description', $sanitizedDescription, \PDO::PARAM_STR);
        $stmt->bindParam(':video_id', $videoId, \PDO::PARAM_STR);
        $stmt->bindParam(':uploaded_by', $sanitizedUserId, \PDO::PARAM_INT);

        $result = $stmt->execute();

        if (!$result) {
            return [
              "status" => "error",
              "message" => "Failed to insert video metadata into database.",
            ];
        }

        return true;
    }

    private function sanatizeTitle($title)
    {
        $title = trim(htmlspecialchars($title));
        $title = strip_tags($title);
        return $title;
    }

    private function sanatizeDescription($description)
    {
        $description = trim(preg_replace('/\s+/', ' ', $description));
        $description = strip_tags($description);
        $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
        return $description;
    }


    private function createDirectory($path)
    {
        if (!is_dir($path)) {
            if (!mkdir($path, 0775, true)) {
                return [
                  "status" => "error",
                  "message" => "Failed to create directory: $path",
                ];
            }
        }

        return true;
    }

    private function generateUniqueId($fileName)
    {
        $rawHash = hash('xxh3', escapeshellcmd($fileName) . time(), true);
        $hash = preg_replace($this->VALID_URL_CHARS, '', base64_encode($rawHash));
        return substr($hash, 0, 8);
    }

    private function cleanupTempFiles($tempFilePath, $tempDir)
    {
        $deleteTempResult = unlink($tempFilePath);
        $deleteTempDirResult = rmdir($tempDir);

        if (!$deleteTempResult || !$deleteTempDirResult) {
            return [
              "status" => "error",
              "message" => "Failed to clean up temporary files.",
            ];
        }

        return true;
    }

    private function cleanupFinalFiles($finalFilePath, $finalDir)
    {
        $deleteFinalResult = unlink($finalFilePath);
        $deleteFinalDirResult = rmdir($finalDir);

        if (!$deleteFinalResult || !$deleteFinalDirResult) {
            return [
              "status" => "error",
              "message" => "Failed to clean up final files.",
            ];
        }

        return true;
    }
}
