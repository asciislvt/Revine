<?php

require_once __DIR__ . '/../../app/VideoQuery.php';

use Revine\VideoQuery;

session_start();

$isLoggedIn = isset($_SESSION['user_id']);
$filter = $_GET['fetchType'] ?? 'recent';

$videoQuery = new VideoQuery();
switch ($filter) {
    case 'recent':
        $videos = $videoQuery->getRecentlyUploaded();
        break;
    case 'trending':
        $videos = $videoQuery->getTrendingVideos();
        break;
    case 'following':
        // error_log("Fetching following videos for user: " . ($_SESSION['user_id'] ?? 'Unknown'));
        if (!$isLoggedIn) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit();
        }
        $videos = $videoQuery->getFollowingVideos($_SESSION['user_id']);
        break;
    case 'byUser':
        $uploader = $_GET['uploader'] ?? null;
        if ($uploader === null) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing username parameter']);
            exit();
        }
        $videos = $videoQuery->getVideosByUsername($uploader);
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid fetch type']);
        exit();
}

http_response_code(200);
echo $videos ? json_encode($videos) : json_encode([]);
exit();