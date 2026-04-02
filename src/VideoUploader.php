<?php

namespace app;

class VideoUploader
{
    private $target_dir = "/data/tmp/";

    public function __construct()
    {
        // if (!is_dir($this->target_dir)) {
        //     mkdir($this->target_dir, 0755, true);
        // }
    }

    public function upload($file)
    {
        $is_file_valid = $this->validateFile($file);
        if (!$is_file_valid) {
            throw new \Exception("Invalid file.");
        }

        $target_file = $this->target_dir . basename($file["name"]);
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return "The file " . htmlspecialchars(basename($file["name"])) . " has been uploaded.";
        } else {
            throw new \Exception("Sorry, there was an error uploading your file.");
        }
    }

    public function validateFile($file): bool
    {
        $allowed_types = ["video/mp4"];
        $file_type = mime_content_type($file["tmp_name"]);

        if (!in_array($file_type, $allowed_types)) {
            throw new \Exception("Only MP4 files are allowed, dumbass.");
        }

        echo "File is Valid!";
        return true;
    }
}
