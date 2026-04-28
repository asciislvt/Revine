<?php

session_start();

require_once __DIR__ . '/../../app/UserQuery.php';

use Revine\UserQuery;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method Not Allowed: Only POST requests are allowed.";
    exit;
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Unauthorized: You must be logged in to edit your profile.";
    exit;
}
