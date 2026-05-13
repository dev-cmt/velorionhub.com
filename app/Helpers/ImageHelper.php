<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImageHelper
{
    public static function uploadImage($file, $folder, $update = null, $width = null, $height = null, $convertToWebp = false)
    {
        $fileUrl = null;

        if (!$file) {
            return $update ?: null;
        }

        // Delete existing file if $update is provided and has a file path
        if ($update) {
            $oldFilePath = public_path($update);
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }

        // Define the directory path
        $directory = public_path($folder);

        // Ensure the directory exists; create if it doesn't
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0777, true, true);
        }

        // Generate a unique base name (extension decided later)
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $baseName = Str::slug($originalName) . '_' . uniqid();

        // Prefer reading directly from the temp upload path.
        $sourcePath = $file->getRealPath() ?: $file->getPathname();

        // Normalize flag: only convert if GD supports WebP output.
        $convertToWebp = (bool) $convertToWebp && function_exists('imagewebp');

        $written = false;
        $uniqueName = null;

        // If resizing and/or WebP conversion requested, try GD pipeline.
        if (($width && $height) || $convertToWebp) {
            $imageInfo = @getimagesize($sourcePath);
            if ($imageInfo) {
                [$origWidth, $origHeight, $type] = $imageInfo;
                $image = null;

                switch ($type) {
                    case IMAGETYPE_JPEG: $image = imagecreatefromjpeg($sourcePath); break;
                    case IMAGETYPE_PNG: $image = imagecreatefrompng($sourcePath); break;
                    case IMAGETYPE_GIF: $image = imagecreatefromgif($sourcePath); break;
                    case IMAGETYPE_WEBP: $image = function_exists('imagecreatefromwebp') ? imagecreatefromwebp($sourcePath) : null; break;
                }

                if ($image) {
                    $targetWidth = ($width && $height) ? (int) $width : (int) $origWidth;
                    $targetHeight = ($width && $height) ? (int) $height : (int) $origHeight;

                    $newImage = imagecreatetruecolor($targetWidth, $targetHeight);

                    if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF || $type == IMAGETYPE_WEBP) {
                        imagecolortransparent($newImage, imagecolorallocatealpha($newImage, 0, 0, 0, 127));
                        imagealphablending($newImage, false);
                        imagesavealpha($newImage, true);
                    }

                    imagecopyresampled(
                        $newImage,
                        $image,
                        0,
                        0,
                        0,
                        0,
                        $targetWidth,
                        $targetHeight,
                        $origWidth,
                        $origHeight
                    );

                    if ($convertToWebp) {
                        $uniqueName = $baseName . '.webp';
                        $destinationPath = $directory . '/' . $uniqueName;
                        $written = imagewebp($newImage, $destinationPath, 90);
                    } else {
                        $uniqueName = $baseName . '.' . $extension;
                        $destinationPath = $directory . '/' . $uniqueName;

                        switch ($type) {
                            case IMAGETYPE_JPEG: $written = imagejpeg($newImage, $destinationPath, 90); break;
                            case IMAGETYPE_PNG: $written = imagepng($newImage, $destinationPath); break;
                            case IMAGETYPE_GIF: $written = imagegif($newImage, $destinationPath); break;
                            case IMAGETYPE_WEBP:
                                $written = function_exists('imagewebp') ? imagewebp($newImage, $destinationPath, 90) : false;
                                break;
                        }
                    }

                    imagedestroy($image);
                    imagedestroy($newImage);

                    if ($written && isset($destinationPath) && file_exists($destinationPath)) {
                        @chmod($destinationPath, 0644);
                    }
                }
            }
        }

        // Fallback: plain copy to keep the temp upload intact (avoids repeated move() errors)
        if (!$written) {
            $uniqueName = $baseName . '.' . $extension;
            $destinationPath = $directory . '/' . $uniqueName;

            if (!@copy($sourcePath, $destinationPath)) {
                // Keep prior behavior as a final fallback.
                $file->move($directory, $uniqueName);
            } else {
                @chmod($destinationPath, 0644);
            }
        }

        return "{$folder}/{$uniqueName}";
    }

    public static function deleteImage($imagePath)
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
            return true;
        }
        return false;
    }
}
