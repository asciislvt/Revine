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
        $userIdQuery = $this->getIdByUsername($username);

        if (!$userIdQuery) {
            return false;
        }

        $stmt = $this->db->prepare("SELECT bio, tagline
                                    FROM profiles
                                    WHERE user_id = :id");

        $stmt->bindParam(':id', $userIdQuery['id'], \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function getProfileInfoByUserId($userId)
    {
        $stmt = $this->db->prepare("SELECT bio, tagline
                                    FROM profiles
                                    WHERE user_id = :id");

        $stmt->bindParam(':id', $userId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function createUser($email, $username, $passhash)
    {
        if ($this->getIdByUsername($username)) {
            return false;
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

    public function updateProfile($userId, $bio = null, $tagline = null)
    {
        if ($bio === null && $tagline === null) {
            return false;
        }

        $existingProfile = $this->getProfileInfoByUsername($this->getUsernameById($userId));

        if ($bio === null) {
            $newBio = $existingProfile['bio'];
        }

        if ($tagline === null) {
            $newTagline = $existingProfile['tagline'];
        }

        $stmt = $this->db->prepare("UPDATE profiles 
                                    SET bio = :bio, tagline = :tagline
                                    WHERE user_id = :user_id");

        $stmt->bindParam(':bio', $newBio, \PDO::PARAM_STR);
        $stmt->bindParam(':tagline', $newTagline, \PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updateProfilePicture($userId, $picturePath)
    {
        $username = $this->getUsernameById($userId);

        $destinationPath = "/data/users/$username/profile.jpg";

        if (!move_uploaded_file($picturePath, $destinationPath)) {
            return false;
        }

        return true;
    }
}
