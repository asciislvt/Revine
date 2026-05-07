<?php

namespace Revine;

use Revine\DbConnection;

class FollowQuery
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/../app/DbConnection.php';
        $this->db = DbConnection::getInstance()->getConnection();
    }

    public function isFollowing($followerId, $userId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*)
                                    FROM followers
                                    WHERE following_user = :follower_id AND user_id = :user_id");

        $stmt->bindParam(':follower_id', $followerId, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function follow($followerId, $userId)
    {
        $existingFollow = $this->isFollowing($followerId, $userId);

        if ($existingFollow) {
            return true; // Already following, no action needed
        }


        $stmt = $this->db->prepare("INSERT INTO followers (following_user, user_id) VALUES (:follower_id, :user_id)");
        $stmt->bindParam(':follower_id', $followerId, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function unfollow($followerId, $userId)
    {
        $stmt = $this->db->prepare("DELETE FROM followers WHERE following_user = :follower_id AND user_id = :user_id");
        $stmt->bindParam(':follower_id', $followerId, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getFollowerCount($username)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM followers f
                                    JOIN users u ON f.user_id = u.id
                                    WHERE u.username = :username");

        $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getFollowingList($userId)
    {
        $stmt = $this->db->prepare("SELECT u.username FROM followers f
                                    JOIN users u ON f.user_id = u.id
                                    JOIN users target ON f.following_user = target.id
                                    WHERE target.id = :user_id");

        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        // $log = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        // error_log("Following list result: " . print_r($log, true)); // Debug log
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}