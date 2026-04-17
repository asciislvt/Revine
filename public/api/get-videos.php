<?php

require_once __DIR__ . '/../../app/VideoQuery.php';

use Revine\VideoQuery;

$videoQuery = new VideoQuery();
$videos = $videoQuery->getRecentlyUploaded();

http_response_code(200);
echo $videos ? json_encode($videos) : json_encode([]);
exit();
