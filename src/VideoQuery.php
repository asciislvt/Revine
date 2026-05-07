<?php

namespace Revine;

use Revine\DbConnection,

    Revine\UserQuery,
    
    Revine\FollowQuery;

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

    public function getTrendingVideos()
    {
        $stmt = $this->db->prepare("SELECT v.video_id, v.title, v.description, v.uploaded_on, u.username, COUNT(l.video_id) AS total_likes
                                    FROM videos v
                                    JOIN users u ON u.id = v.uploaded_by
                                    LEFT JOIN likes l ON v.video_id = l.video_id
                                    GROUP BY v.video_id
                                    ORDER BY total_likes DESC
                                    LIMIT 10 OFFSET 0;");

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getFollowingVideos($userId)
    {
        require_once __DIR__ . '/FollowQuery.php';
        // error_log("Getting following videos for user ID: $userId"); // Debug log
        $followQuery = new FollowQuery();
        $followingList = $followQuery->getFollowingList($userId);
        // error_log("Following list for user ID $userId: " . print_r($followingList, true)); // Debug log
            if (empty($followingList)) {
                // error_log("User ID $userId is not following anyone."); // Debug log
                return []; // No following users, return empty array
            } else {
                // error_log("User ID $userId is following " . count($followingList) . " users."); // Debug log
                $placeholders = implode(',', array_fill(0, count($followingList), '?'));
                $stmt = $this->db->prepare("SELECT v.video_id, v.title, v.description, v.uploaded_on, u.username
                                            FROM videos v
                                            JOIN users u ON u.id = v.uploaded_by
                                            WHERE u.username IN ($placeholders)
                                            ORDER BY v.uploaded_on DESC
                                            LIMIT 10 OFFSET 0;");

                foreach ($followingList as $index => $username) {
                    $stmt->bindValue($index + 1, $username, \PDO::PARAM_STR);
                }

                $stmt->execute();
                return $stmt->fetchAll();
            }
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