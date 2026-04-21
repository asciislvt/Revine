<?php

namespace Revine;

class VideoTranscoder
{
    public function __construct()
    {
        exec("ffmpeg -version", $output, $returnCode);
        if ($returnCode !== 0) {
            throw new \Exception("FFmpeg is not installed or not available in the system PATH.");
        }
    }

    public function transcodeVideo($inputPath, $outputPath)
    {
        $inputPath = escapeshellarg($inputPath);
        $outputPath = escapeshellarg($outputPath);

        $ffmpegCommand = "ffmpeg -i $inputPath -t 10 \
                          -vf \"scale=1080:1350:force_original_aspect_ratio=decrease,\
                          pad=1080:1350:(ow-iw)/2:(oh-ih)/2\" \
                          -c:v libsvtav1 -crf 30 -preset 6 \
                          -pix_fmt yuv420p \
                          -c:a aac -b:a 128k \
                          -movflags +faststart \
                          $outputPath";

        exec($ffmpegCommand, $output, $returnCode);

        if ($returnCode !== 0) {
            return [
              "status" => "error",
              "message" => "Video transcoding failed with error code: $returnCode",
            ];
        }

        return true;
    }

    public function generateThumbnail($inputPath, $thumbnailPath)
    {
        $inputPath = escapeshellarg($inputPath);
        $thumbnailPath = escapeshellarg($thumbnailPath);

        $ffmpegCommand = "ffmpeg -i $inputPath \
                          -vf \"select='gte(t,2)',scale=320:-1\" \
                          -frames:v 1 $thumbnailPath";

        exec($ffmpegCommand, $output, $returnCode);

        if ($returnCode !== 0) {
            return [
              "status" => "error",
              "message" => "Thumbnail generation failed with error code: $returnCode",
            ];
        }

        return true;
    }
}
