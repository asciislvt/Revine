<?php

namespace Revine;

use Revine\DbConnection;

class CommentQuery
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/DbConnection.php';
        $this->db = DbConnection::getInstance()->getConnection();
    }

    public function getCommentsByVideoId($videoId)
    {
        $stmt = $this->db->prepare('
            SELECT c.id, c.comment, c.comment_date, u.username
            FROM comments c
            JOIN users u ON u.id = c.user_id
            WHERE c.video_id = :video_id
            ORDER BY c.comment_date DESC
        ');
        $stmt->execute(['video_id' => $videoId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getRepliesByCommentId($commentId)
    {
        $stmt = $this->db->prepare('
            SELECT c.id, c.comment, c.comment_date, u.username
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.parent_comment = :comment_id
            ORDER BY c.comment_date ASC
        ');
        $stmt->execute(['comment_id' => $commentId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function addComment($videoId, $userId, $commentText)
    {
        $stmt = $this->db->prepare('
            INSERT INTO comments (video_id, user_id, comment)
            VALUES (:video_id, :user_id, :comment_text)
        ');
        return $stmt->execute([
            'video_id' => $videoId,
            'user_id' => $userId,
            'comment_text' => $commentText
        ]);
    }

    public function addReply($videoId, $userId, $commentText, $parentCommentId)
    {
        $stmt = $this->db->prepare('
            INSERT INTO comments (video_id, user_id, comment, parent_comment)
            VALUES (:video_id, :user_id, :comment_text, :parent_comment_id)
        ');
        return $stmt->execute([
            'video_id' => $videoId,
            'user_id' => $userId,
            'comment_text' => $commentText,
            'parent_comment_id' => $parentCommentId
        ]);
    }
}
