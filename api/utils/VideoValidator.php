<?php

namespace Revine;

class VideoValidator
{
  # Checks if the video is valid
  # returns an array of errors if not, otherwise returns true
    public static function isVideoValid($video)
    {
        if (!$video) {
            return ['error' => 'No video uploaded!'];
        } elseif (!self::validateFileType($video)) {
            return ['error' => 'Invalid file type!'];
        } elseif (!self::validateFileSize($video)) {
            return ['error' => 'File size exceeds the limit!'];
        } elseif (!self::validateUploadError($video)) {
            return ['error' => 'Error during file upload!'];
        } else {
            return true;
        }
    }

    private static function validateFileType($video)
    {
        $pattern = "/video\//";
        return preg_match($pattern, $video['type']);
    }

    private static function validateFileSize($video)
    {
        return $video['size'] <= 300000000;
    }

    private static function validateUploadError($video)
    {
        return $video['error'] === UPLOAD_ERR_OK;
    }
}
