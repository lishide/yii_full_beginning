<?php

/**
 * Created by PhpStorm.
 * User: Hapon
 * Date: 2016/9/24
 * Time: 上午12:16
 */
class ImageResize extends CComponent
{
    public function resize($sourcePath, $targetPath, $maxLength = 1000, $triggerSize = 500, $quality = 80){
        $timeStart = explode(' ', microtime());

        if(!file_exists($sourcePath) || !is_file($sourcePath)) {
            return false;
        }

        if(empty($targetPath)){
            $pathParts = pathinfo($sourcePath);
            $targetPath = sprintf('%s/%s_thumb.%s', $pathParts['dirname'], $pathParts['filename'], $pathParts['extension']);
        }

        $targetDirName = pathinfo($targetPath, PATHINFO_DIRNAME);
        if (!file_exists($targetDirName))
            mkdir($targetDirName, 0777, true);

        $sourceSize = filesize($sourcePath);
        $sourceInfo = getimagesize($sourcePath);

        $sourceWidth = $sourceInfo['0'];
        $sourceHeight = $sourceInfo['1'];
        $sourceMime = $sourceInfo['mime'];

        $sourceImage = null;
        switch ($sourceMime)
        {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            default:
                return false;
                break;
        }

        $targetWidth = 0;
        $targetHeight = 0;

        if($sourceWidth >= $sourceHeight && $sourceWidth > $maxLength){
            $targetWidth = $maxLength;
            $targetHeight = 1.0 * $maxLength / $sourceWidth * $sourceHeight;
        }else if($sourceHeight >= $sourceWidth && $sourceHeight > $maxLength){
            $targetHeight = $maxLength;
            $targetWidth = 1.0 * $maxLength / $sourceHeight * $sourceWidth;
        }else if($sourceSize > $triggerSize * 1024){
            $targetWidth = $sourceWidth;
            $targetHeight = $sourceHeight;
        }

        if($targetWidth > 0 && $targetHeight > 0){
            $targetImage  = imagecreatetruecolor($targetWidth, $targetHeight);

            // 缩放
            imagecopyresampled($targetImage, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);

            imagejpeg($targetImage, $targetPath, $quality);
            imagedestroy($sourceImage);
            imagedestroy($targetImage);

            $timeEnd = explode(' ', microtime());

            return [
                'time' => $timeEnd[0] + $timeEnd[1] - $timeStart[0] - $timeStart[1],
                'sourceSize' => $sourceSize,
                'targetSize' => filesize($targetPath),
                'sourcePath' => $sourcePath,
                'targetPath' => $targetPath,
            ];
        }else{
            return false;
        }
    }
}