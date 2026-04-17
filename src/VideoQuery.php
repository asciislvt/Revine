<?php

namespace Revine;

use Revine\DbConnection,

    Revine\UserQuery;

class VideoQuery
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/DbConnection.php';
        $this->db = DbConnection::getInstance()->getConnection();
    }

    public function getRecentlyUploaded()
    {

        $stmt = $this->db->prepare("SELECT v.video_id, v.title, v.description, v.uploaded_on, u.username
                                    FROM videos v
                                    JOIN users u ON u.id = v.uploaded_by
                                    ORDER BY v.uploaded_on DESC
                                    LIMIT 10 OFFSET 0;");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getTrending()
    {
        $stmt = $this->db->prepare("SELECT v.video_id, v.title, v.description, v.uploaded_on, u.username
                                    FROM videos v
                                    JOIN users u ON u.id = v.uploaded_by
                                    ORDER BY v.view_count DESC
                                    LIMIT 10 OFFSET 0;");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getVideoById($videoId)
    {
        $stmt = $this->db->prepare("SELECT v.video_id, v.title, v.description, v.uploaded_on, u.username
                                    FROM videos v
                                    JOIN users u ON u.id = v.uploaded_by
                                    WHERE v.video_id = :videoId");

        $stmt->bindParam(':videoId', $videoId, \PDO::PARAM_INT);
        $stmt->execute();

        $video = $stmt->fetch();

        if (!$video) {
            return null; // Video not found
        }

        $video['username'] = $video['username'] ?? 'Unknown';
        $video['video_url'] = "/videos/{$video['video_id']}/{$video['video_id']}.mp4";
        $video['thumbnail_url'] = "/videos/{$video['video_id']}/thumbnail.jpg";
        $video['uploade_date'] = date("F j, Y", strtotime($video['uploaded_on']));

        return $video;
    }
}
