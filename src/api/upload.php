<?php

// We'll used this later to save the upload to server ;)
// $uploaded_video = $_FILES['uploaded-video'];

$video_title = $_POST['video-title'];
$video_description = $_POST['video-description'];

$data = [
  'video_title' => $video_title,
  'video_description' => $video_description,
];

// Testing request body parsing and response sending
echo json_encode($data);
