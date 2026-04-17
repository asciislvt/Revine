<?php

require_once __DIR__ . '/../../app/UserQuery.php';

use Revine\UserQuery;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit();
}

if (!isset($_POST['email'], $_POST['username'], $_POST['password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}

// TODO: Add more validation for email, username and password (length, complexity, etc.)

$email = $_POST['email'];
$username = $_POST['username'];
$passhash = password_hash($_POST['password'], PASSWORD_ARGON2I);

$userQuery = new UserQuery();

$userCreated = $userQuery->createUser($email, $username, $passhash);

if ($userCreated) {
    http_response_code(201);
    echo json_encode(['message' => 'User created successfully']);
} else {
    http_response_code(409);
    echo json_encode(['error' => 'Username already exists']);
}
