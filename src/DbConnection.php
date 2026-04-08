<?php

namespace Revine;

use PDO,

    Exception;

class DbConnection
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $host = getenv('DB_HOST');
        $dbname = getenv('DB_NAME');

        $dsn = "mysql:host=$host;dbname=$dbname";
        $username = getenv('DB_USER');
        $password = getenv('DB_PASS');

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->connection = new \PDO($dsn, $username, $password, $options);
        } catch (\PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
