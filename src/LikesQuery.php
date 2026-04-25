<?php

namespace Revine;

use Revine\DbConnection;

class LikesQuery
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/../app/DbConnection.php';
        $this->db = DbConnection::getInstance()->getConnection();
    }

    public function addLike($userId, $videoId, $isLike)
    {
        $stmt = $this->db->prepare("INSERT INTO likes (user_id, video_id, is_like)
                                    VALUES (:user_id, :video_id, :is_like)
                                    ");
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':video_id', $videoId, \PDO::PARAM_STR);
        $stmt->bindParam(':is_like', $isLike, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getLikesCount($videoId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS likes_count 
                                    FROM likes WHERE video_id = :video_id 
                                    AND is_like = true");
        $stmt->bindParam(':video_id', $videoId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC)['likes_count'] ?? 0;
    }

    public function getDislikesCount($videoId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS dislikes_count
                                    FROM likes WHERE video_id = :video_id
                                    AND is_like = false");
        $stmt->bindParam(':video_id', $videoId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC)['dislikes_count'] ?? 0;
    }
}
