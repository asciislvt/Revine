<?php

namespace Revine;

class VideoValidator
{
    public static function validate($videoFile)
    {
        $filePath = $videoFile['tmp_name'] ?? null;
        if ($filePath === null && is_uploaded_file($filePath)) {
            return "No video file uploaded.";
        } elseif (file_exists($filePath)) {
            $probeCommand = (
            "ffprobe -v quiet -print_format json -select_streams v:0 -show_streams " . escapeshellarg($filePath)
            );
            $probeOutput = shell_exec($probeCommand);
            $probeJson = json_decode($probeOutput, true);

            if (!self::validateVideoType($videoFile, $probeJson)) {
                return "Invalid video type. Please upload a valid video file.";
            } elseif (!self::validateVideoSize($videoFile)) {
                return "Video file size exceeds the limit of 300mb.";
            } elseif (!self::validateVideoDuration($probeJson)) {
                return "Video duration exceeds the allowed limit.";
            } else {
                return true;
            }
        } else {
            return "No video file uploaded.";
        }
    }

    private static function validateVideoType($videoFile, $probe = null)
    {
        $pattern = '/^video\//';
        $isVideoMimeType = preg_match($pattern, $videoFile['type']);
        $isVideoProbe = false;

        if ($probe) {
            if (isset($probe['streams'][0]['codec_type']) && $probe['streams'][0]['codec_type'] === 'video') {
                $isVideoProbe = true;
            }
        }

        return $isVideoMimeType && $isVideoProbe;
    }

    private static function validateVideoSize($videoFile)
    {
        return $videoFile['size'] <= 300000000;
    }

    private static function validateVideoDuration($probe)
    {
        if ($probe) {
            $duration = $probe['streams'][0]['duration'] ?? null;
            if ($duration !== null) {
                return $duration <= 10; // 10 second duration limit for videos
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
