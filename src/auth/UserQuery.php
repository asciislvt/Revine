<?php

namespace Revine\Auth;

use Revine\DbConnection;

class UserQuery
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/../DbConnection.php';
        $this->db = DbConnection::getInstance()->getConnection();
    }

    public function getUserByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch();
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

    public function createUser($email, $username, $passhash)
    {
        if ($this->getUserByUsername($username)) {
              return false; // Username already exists
        }

        $stmt = $this->db->prepare("INSERT INTO users (email, username, password_hash)
                                    VALUES (:email, :username, :password)");

        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
        $stmt->bindParam(':password', $passhash, \PDO::PARAM_STR);
        return $stmt->execute();
    }
}
