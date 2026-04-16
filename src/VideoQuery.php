<?php

namespace Revine;

use Revine\DbConnection,

    Revine\Auth\UserQuery;

class VideoQuery
{
    public static function getRecentlyUploaded()
    {
        require_once __DIR__ . '/DbConnection.php';

        $db = DbConnection::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT v.video_id, v.title, v.description, v.uploaded_on, u.username
                              FROM videos v
                              JOIN users u ON u.id = v.uploaded_by
                              ORDER BY v.uploaded_on DESC
                              LIMIT 10 OFFSET 0;");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function getTrending()
    {
        require_once __DIR__ . '/DbConnection.php';

        $db = DbConnection::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT v.video_id, v.title, v.description, v.uploaded_on, u.username
                              FROM videos v
                              JOIN users u ON u.id = v.uploaded_by
                              ORDER BY v.view_count DESC
                              LIMIT 10 OFFSET 0;");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function getVideoMetadata($videoId)
    {
        require_once __DIR__ . '/DbConnection.php';

        $db = DbConnection::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM videos WHERE video_id = :videoId");
        $stmt->bindParam(':videoId', $videoId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

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
        $video['thumbnail_url'] = "/videos/{$video['video_id']}/thumbnail.jpg";
        $video['uploade_date'] = date("F j, Y", strtotime($video['uploaded_on']));

        return $video;
    }
}
