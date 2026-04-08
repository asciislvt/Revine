<?php

use Revine\Auth\Authentiaction;

require_once __DIR__ . '/../../app/auth/Authentication.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit();
}

if (!isset($_POST['username'], $_POST['password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}

$username = $_POST['username'];
$auth = new Authentiaction();

$userAuth = $auth->authenticate($username, $_POST['password']);

if (!$userAuth) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid username or password']);
    exit();
} else {
    session_start();
    session_regenerate_id(true);
    $_SESSION['user_id'] = $userAuth['userId'];
    http_response_code(200);
    echo json_encode(['success' => true]);
}
