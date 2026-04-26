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

    public function getVideosByUsername($username)
    {
        include_once __DIR__ . '/UserQuery.php';
        $userQuery = new UserQuery();

        $userId = $userQuery->getUserIdByUsername($username);
        if ($userId === null) {
            return null; // User not found
        }

        return $this->getVideosByUserId($userId);
    }

    public function searchVideos($searchTerm)
    {
        $stmt = $this->db->prepare("SELECT video_id, title, description, uploaded_on 
                                    FROM videos
                                    WHERE title LIKE :searchTerm 
                                    OR description LIKE :searchTerm
                                    ORDER BY uploaded_on DESC");
        $likeTerm = '%' . $searchTerm . '%';
        $stmt->bindParam(':searchTerm', $likeTerm, \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getVideosByCategory($categoryId)
    {
        $stmt = $this->db->prepare("SELECT video_id, title, description, uploaded_on
                                    FROM videos
                                    WHERE category = :categoryId
                                    ORDER BY uploaded_on DESC");
        $stmt->bindParam(':categoryId', $categoryId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
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

    public function getVideosByUserId($userId)
    {
        $stmt = $this->db->prepare("SELECT v.video_id, v.title, v.description, v.uploaded_on, u.username
                                    FROM videos v
                                    JOIN users u ON u.id = v.uploaded_by
                                    WHERE v.uploaded_by = :userId
                                    ORDER BY v.uploaded_on DESC");
        $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
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
        $stmt = $this->db->prepare("SELECT v.video_id, v.title, v.description, v.uploaded_on, u.username, c.category_name
                                    FROM videos v
                                    JOIN users u ON u.id = v.uploaded_by
                                    JOIN categories c ON c.id = v.category
                                    WHERE v.video_id = :videoId");

        $stmt->bindParam(':videoId', $videoId, \PDO::PARAM_STR);
        $stmt->execute();

        $video = $stmt->fetch();

        if (!$video) {
            return null; // Video not found
        }

        $video['username'] = $video['username'] ?? 'Unknown';
        $video['video_url'] = "/videos/{$video['video_id']}/{$video['video_id']}.mp4";
        $video['thumbnail_url'] = "/videos/{$video['video_id']}/thumbnail.jpg";
        $video['upload_date'] = date("F j, Y", strtotime($video['uploaded_on']));

        return $video;
    }
}
