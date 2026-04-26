<?php

namespace Revine;

use Revine\DbConnection;

class UserQuery
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/DbConnection.php';
        $this->db = DbConnection::getInstance()->getConnection();
    }

    public function getIdByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function getUsernameById($userId)
    {
        $stmt = $this->db->prepare("SELECT username FROM users WHERE id = :id");
        $stmt->bindParam(':id', $userId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function getUserIdByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function getPassHashByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT password_hash FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function getProfileInfoByUsername($username)
    {
        $userId = $this->getIdByUsername($username);
        if (!$userId) {
            return null; // User not found
        }

        $stmt = $this->db->prepare("SELECT bio, tagline FROM profiles WHERE user_id = :id");
        $stmt->bindParam(':id', $userId['id'], \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function createUser($email, $username, $passhash)
    {
        if ($this->getIdByUsername($username)) {
            return false; // Username already exists
        }

        $stmt = $this->db->prepare("INSERT INTO users (email, username, password_hash)
                                    VALUES (:email, :username, :password)");

        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
        $stmt->bindParam(':password', $passhash, \PDO::PARAM_STR);
        $queryResult = $stmt->execute();

        if ($queryResult) {
            $userId = $this->getIdByUsername($username)['id'];
            if (!mkdir('/data/users/' . $username, 0755, true)) {
                // Rollback user creation if directory creation fails
                $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
                $stmt->bindParam(':id', $userId, \PDO::PARAM_INT);
                $stmt->execute();

                http_response_code(500);
                echo json_encode(['error' => 'Failed to create user directory']);
                exit();
            }

            $stmt = $this->db->prepare("INSERT INTO profiles (user_id) VALUES (:user_id)");
            $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
            $stmt->execute();
        }
        return $queryResult;
    }
}
