<?php

namespace Revine;

class VideoUploader
{
    public static function upload($videoFile, $title, $description, $userId)
    {
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

        $TEMP_DIR = '/data/tmp/';
        $UPLOAD_DIR = '/data/videos/';

        // Create unique ID for video
        $videoId = self::generateUniqueId($videoFile['name']);

        // Create temp and final directories and file paths
        $fileExtenstion = pathinfo($videoFile['name'], PATHINFO_EXTENSION);

        $tempDir = $TEMP_DIR . $videoId;
        $tempFilePath = $tempDir . '/' . $videoId . '_src.' . $fileExtenstion;
        $tempDirResult = self::createDirectory($tempDir);

        if ($tempDirResult !== true) {
            return $tempDirResult;
        }

        $finalDir = $UPLOAD_DIR . $videoId;
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

        // TRANSCODE VIDEO
        $transcodeResult = self::transcodeVideo($tempFilePath, $finalFilePath);

        if ($transcodeResult !== true) {
            return $transcodeResult;
        }

        $deleteTempResult = unlink($tempFilePath);
        $deleteTempDirResult = rmdir($tempDir);

        if (!$deleteTempResult || !$deleteTempDirResult) {
            return [
              "status" => "error",
              "message" => "Failed to clean up temporary files.",
            ];
        }

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

    private static function databaseInsert($videoId, $title, $description, $userId)
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

    private static function sanatizeTitle($title)
    {
        $title = trim(htmlspecialchars($title));
        $title = strip_tags($title);
        return $title;
    }

    private static function sanatizeDescription($description)
    {
        $description = trim(preg_replace('/\s+/', ' ', $description));
        $description = strip_tags($description);
        $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
        return $description;
    }

    private static function transcodeVideo($inputPath, $outputPath)
    {
        $ffmpegCommand = "ffmpeg -i $inputPath -t 10 \
                        -vf \"scale=1080:1350:force_original_aspect_ratio=decrease,\
                        pad=1080:1350:(ow-iw)/2:(oh-ih)/2\" \
                        -c:v libsvtav1 -crf 30 -preset 6 \
                        -pix_fmt yuv420p \
                        -c:a aac -b:a 128k \
                        -movflags +faststart \
                        $outputPath";

        exec($ffmpegCommand, $output, $returnCode);

        if ($returnCode !== 0) {
            return [
              "status" => "error",
              "message" => "Video transcoding failed with error code: $returnCode",
            ];
        }

        return true;
    }

    private static function createDirectory($path)
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

    private static function generateUniqueId($fileName)
    {
        $VALID_URL_CHARS = '/[^A-Za-z0-9_-]/iu';
        $rawHash = hash('xxh3', escapeshellcmd($fileName) . time(), true);
        $hash = preg_replace($VALID_URL_CHARS, '', base64_encode($rawHash));
        return substr($hash, 0, 8);
    }
}
