<?php

namespace Revine\Auth;

use Revine\Auth\UserQuery;

class Authentiaction
{
    private $userQuery;

    public function __construct()
    {
        require_once __DIR__ . '/UserQuery.php';
        $this->userQuery = new UserQuery();
    }

    public function authenticate($username, $password)
    {
        $storedHash = $this->userQuery->getPassHashByUsername($username);

        if (!$storedHash) {
            return false; // User not found
        }

        if (!password_verify($password, $storedHash)) {
            return false; // Invalid password
        }

        return password_verify($password, $storedHash) ? [
            "isValid" => true,
            "userId" => $this->userQuery->getUserIdByUsername($username)
        ] : false;
    }
}
