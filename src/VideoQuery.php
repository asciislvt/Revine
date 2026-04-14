<?php

namespace Revine;

use Revine\DbConnection,

    Revine\Auth\UserQuery;

class VideoQuery
{
    public static function getVideoById($videoId)
    {
        require_once __DIR__ . '/DbConnection.php';
        require_once __DIR__ . '/auth/UserQuery.php';

        $db = DbConnection::getInstance()->getConnection();
        $userQuery = new UserQuery();

        $stmt = $db->prepare("SELECT * FROM videos WHERE video_id = :videoId");
        $stmt->bindParam(':videoId', $videoId, \PDO::PARAM_INT);
        $stmt->execute();

        $video = $stmt->fetch();

        if (!$video) {
            return null; // Video not found
        }

        $video['username'] = $userQuery->getUsernameById($video['uploaded_by']);
        $video['video_url'] = "/videos/{$video['video_id']}/{$video['video_id']}.mp4";

        return $video;
    }
}
