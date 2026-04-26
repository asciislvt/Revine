<?php

require_once __DIR__ . '/../../app/VideoQuery.php';

$category = $_GET['category'] ?? null;
$uploader = $_GET['uploader'] ?? null;
$search = $_GET['search'] ?? null;

use Revine\VideoQuery;

$videoQuery = new VideoQuery();
if ($category !== null) {
    $videos = $videoQuery->getVideosByCategory($category);
} elseif ($uploader !== null) {
    $videos = $videoQuery->getVideosByUsername($uploader);
    error_log("Fetching videos for uploader: " . $uploader);
} elseif ($search !== null) {
    $videos = $videoQuery->searchVideos($search);
} else {
    $videos = $videoQuery->getRecentlyUploaded();
}

http_response_code(200);
echo $videos ? json_encode($videos) : json_encode([]);
exit();
