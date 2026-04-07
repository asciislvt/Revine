<?php

namespace Revine\App\Auth;

use Revine\App\DbConnection;

class UserQuery
{
    private $db;

    public function __construct()
    {
        $this->db = DbConnection::getInstance()->getConnection();
    }

    public function getUserByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function createUser($username, $passhash)
    {
        $stmt = $this->db->prepare("INSERT INTO users (username, pass_hash)
                                    VALUES (:username, :password)");

        $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
        $stmt->bindParam(':password', $passhash, \PDO::PARAM_STR);
        return $stmt->execute();
    }
}
