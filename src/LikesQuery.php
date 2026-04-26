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
        $hasLiked = $this->hasUserLiked($userId, $videoId, $isLike);
        if ($hasLiked === null) {
            return $this->insertLike($userId, $videoId, $isLike);
        } elseif ($hasLiked != $isLike) {
            return $this->updateLike($userId, $videoId, $isLike);
        } else {
            return $this->removeLike($userId, $videoId);
        }
    }

    public function hasUserLiked($userId, $videoId, $isLike = true)
    {
        $stmt = $this->db->prepare("SELECT is_like FROM likes
                                    WHERE user_id = :user_id AND video_id = :video_id AND is_like = :is_like");
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':video_id', $videoId, \PDO::PARAM_STR);
        $stmt->bindParam(':is_like', $isLike, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC)['is_like'] ?? null;
    }

    private function insertLike($userId, $videoId, $isLike)
    {
        $stmt = $this->db->prepare("INSERT INTO likes (user_id, video_id, is_like)
                                    VALUES (:user_id, :video_id, :is_like)");
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':video_id', $videoId, \PDO::PARAM_STR);
        $stmt->bindParam(':is_like', $isLike, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateLike($userId, $videoId, $isLike)
    {
        $stmt = $this->db->prepare("UPDATE likes SET is_like = :is_like
                                    WHERE user_id = :user_id AND video_id = :video_id");
        $stmt->bindParam(':is_like', $isLike, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':video_id', $videoId, \PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function removeLike($userId, $videoId)
    {
        $stmt = $this->db->prepare("DELETE FROM likes 
                                    WHERE user_id = :user_id AND video_id = :video_id");
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':video_id', $videoId, \PDO::PARAM_STR);
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
