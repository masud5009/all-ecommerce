<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrCodeService
{
    /**
     * Generate QR code using chillerlan/php-qrcode (local, fast, reliable)
     * Falls back to API if library fails
     */
    public function generateQR($data, $filename, $directory, $options = [])
    {
        try {
            // Default options
            $defaultOptions = [
                'size' => 300,
                'color' => '#000000',
                'margin' => 1,
                'background_color' => '#FFFFFF',
            ];

            $options = array_merge($defaultOptions, $options);

            // Validate values
            $size = (int) ($options['size'] ?? 300);
            $size = max(200, min(350, $size));

            $margin = (int) ($options['margin'] ?? 1);
            $margin = max(0, min(5, $margin));

            // Ensure directory exists
            // $directory = public_path('assets/img/tables/');
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $filePath = $directory . $filename;

            // Try local generation first
            try {
                $this->generateQRLocal($data, $filePath, $size, $margin, $options);
            } catch (\Exception $e) {
                // Fallback to API
                $this->generateQRFromAPI($data, $filePath, $size, $margin);

                // Apply custom color if not black
                if ($options['color'] !== '#000000') {
                    $this->recolorQR($filePath, $options['color'], $options['background_color']);
                }
            }

            if (!File::exists($filePath)) {
                throw new \Exception('QR code file was not created');
            }

            // Add image if needed
            if (isset($options['image_path']) && File::exists($options['image_path'])) {
                $this->mergeImageToQR($filePath, $options);
            }

            // Add text if needed
            if (isset($options['text']) && !empty($options['text'])) {
                $this->addTextToQR($filePath, $options);
            }

            return $filename;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Generate QR locally using chillerlan/php-qrcode
     */
    private function generateQRLocal($data, $filePath, $size, $margin, $options)
    {
        // Check if chillerlan is installed
        if (!class_exists('chillerlan\QRCode\QRCode')) {
            throw new \Exception('chillerlan/php-qrcode not installed');
        }

        $color = $this->validateColor($options['color']);
        $bgColor = $this->validateColor($options['background_color']);

        // Simplified QROptions without the problematic constants
        $qrOptions = new QROptions([
            'version'             => 5,
            'outputType'          => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'            => QRCode::ECC_L,
            'scale'               => (int)($size / 50), // Scale based on size
            'imageBase64'         => false,
            'imageTransparent'    => false,
            'drawCircularModules' => ($options['style'] === 'round'),
            'circleRadius'        => 0.45,
            'moduleValues'        => [
                // Dark modules (QR code)
                1536 => [$color[0], $color[1], $color[2]], // data
                6    => [$color[0], $color[1], $color[2]], // finder
                5    => [$color[0], $color[1], $color[2]], // alignment
                // Light modules (background)
                4    => [$bgColor[0], $bgColor[1], $bgColor[2]],
            ],
            'addQuietzone'        => true,
            'quietzoneSize'       => $margin,
        ]);

        $qrcode = new QRCode($qrOptions);
        $qrcode->render($data, $filePath);

        // Resize to exact size
        $this->resizeImage($filePath, $size, $size);
    }

    /**
     * Generate QR using external API (fallback)
     */
    private function generateQRFromAPI($data, $filePath, $size, $margin)
    {
        $encodedData = urlencode($data);
        $apiUrl = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$encodedData}&margin={$margin}&format=png";

        Log::info('Fetching QR from API: ' . $apiUrl);

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $imageData = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($httpCode !== 200 || !$imageData) {
            throw new \Exception('Failed to fetch QR from API. HTTP Code: ' . $httpCode . ', Error: ' . $error);
        }

        file_put_contents($filePath, $imageData);
    }

    /**
     * Resize image to exact dimensions
     */
    private function resizeImage($filePath, $width, $height)
    {
        try {
            $source = imagecreatefrompng($filePath);
            if (!$source) return;

            $sourceWidth = imagesx($source);
            $sourceHeight = imagesy($source);

            if ($sourceWidth === $width && $sourceHeight === $height) {
                imagedestroy($source);
                return;
            }

            $dest = imagecreatetruecolor($width, $height);
            imagecopyresampled($dest, $source, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);

            imagepng($dest, $filePath);
            imagedestroy($source);
            imagedestroy($dest);
        } catch (\Exception $e) {
            //throw $e;
        }
    }

    /**
     * Change QR code color
     */
    private function recolorQR($filePath, $hexColor, $bgHexColor)
    {
        try {
            $image = imagecreatefrompng($filePath);
            if (!$image) return false;

            $color = $this->validateColor($hexColor);
            $bgColor = $this->validateColor($bgHexColor);

            $newColor = imagecolorallocate($image, $color[0], $color[1], $color[2]);
            $newBgColor = imagecolorallocate($image, $bgColor[0], $bgColor[1], $bgColor[2]);

            $width = imagesx($image);
            $height = imagesy($image);

            for ($x = 0; $x < $width; $x++) {
                for ($y = 0; $y < $height; $y++) {
                    $rgb = imagecolorat($image, $x, $y);
                    $colors = imagecolorsforindex($image, $rgb);

                    $brightness = ($colors['red'] + $colors['green'] + $colors['blue']) / 3;

                    if ($brightness < 128) {
                        imagesetpixel($image, $x, $y, $newColor);
                    } else {
                        imagesetpixel($image, $x, $y, $newBgColor);
                    }
                }
            }

            imagepng($image, $filePath);
            imagedestroy($image);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Merge logo image to QR
     */
    private function mergeImageToQR($qrPath, $options)
    {
        try {
            if (!File::exists($qrPath) || !File::exists($options['image_path'])) {
                return false;
            }

            $qrImage = imagecreatefrompng($qrPath);
            if (!$qrImage) return false;

            $logoPath = $options['image_path'];
            $imageInfo = getimagesize($logoPath);

            $logoImage = null;
            switch ($imageInfo[2]) {
                case IMAGETYPE_JPEG:
                    $logoImage = imagecreatefromjpeg($logoPath);
                    break;
                case IMAGETYPE_PNG:
                    $logoImage = imagecreatefrompng($logoPath);
                    break;
                case IMAGETYPE_GIF:
                    $logoImage = imagecreatefromgif($logoPath);
                    break;
            }

            if (!$logoImage) {
                imagedestroy($qrImage);
                return false;
            }

            $qrWidth = imagesx($qrImage);
            $qrHeight = imagesy($qrImage);
            $logoWidth = imagesx($logoImage);
            $logoHeight = imagesy($logoImage);

            $imageSize = (float) ($options['image_size'] ?? 0.3);
            $imageSize = max(0.1, min(0.5, $imageSize));

            $newLogoWidth = (int) ($qrWidth * $imageSize);
            $newLogoHeight = (int) ($logoHeight * ($newLogoWidth / $logoWidth));

            $image_x = (int) ($options['image_x'] ?? 50);
            $image_y = (int) ($options['image_y'] ?? 50);

            $logoX = (int) (($qrWidth * $image_x / 100) - ($newLogoWidth / 2));
            $logoY = (int) (($qrHeight * $image_y / 100) - ($newLogoHeight / 2));

            // White background
            $white = imagecolorallocate($qrImage, 255, 255, 255);
            $padding = 5;
            imagefilledrectangle(
                $qrImage,
                $logoX - $padding,
                $logoY - $padding,
                $logoX + $newLogoWidth + $padding,
                $logoY + $newLogoHeight + $padding,
                $white
            );

            imagecopyresampled(
                $qrImage,
                $logoImage,
                $logoX,
                $logoY,
                0,
                0,
                $newLogoWidth,
                $newLogoHeight,
                $logoWidth,
                $logoHeight
            );

            imagepng($qrImage, $qrPath);
            imagedestroy($qrImage);
            imagedestroy($logoImage);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Add text to QR
     */
    private function addTextToQR($imagePath, $options)
    {
        try {
            if (!File::exists($imagePath)) return false;

            $image = imagecreatefrompng($imagePath);
            if (!$image) return false;

            $textColor = $this->validateColor($options['text_color'] ?? '#000000');
            $color = imagecolorallocate($image, $textColor[0], $textColor[1], $textColor[2]);

            $imageWidth = imagesx($image);
            $imageHeight = imagesy($image);

            $text_x = (int) ($options['text_x'] ?? 50);
            $text_y = (int) ($options['text_y'] ?? 50);
            $text_size = (int) ($options['text_size'] ?? 5);
            $text = $options['text'] ?? '';

            $fontSize = max(1, min(5, $text_size));

            $textWidth = imagefontwidth($fontSize) * strlen($text);
            $textHeight = imagefontheight($fontSize);

            $x = (int) (($imageWidth * $text_x / 100) - ($textWidth / 2));
            $y = (int) (($imageHeight * $text_y / 100) - ($textHeight / 2));

            // White background
            $white = imagecolorallocate($image, 255, 255, 255);
            $padding = 3;
            imagefilledrectangle(
                $image,
                $x - $padding,
                $y - $padding,
                $x + $textWidth + $padding,
                $y + $textHeight + $padding,
                $white
            );

            imagestring($image, $fontSize, $x, $y, $text, $color);

            imagepng($image, $imagePath);
            imagedestroy($image);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function validateColor($hex)
    {
        $hex = str_replace('#', '', $hex);
        if (!ctype_xdigit($hex) || (strlen($hex) != 3 && strlen($hex) != 6)) {
            $hex = '000000';
        }
        return $this->hexToRgb($hex);
    }

    private function hexToRgb($hex)
    {
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return [(int)$r, (int)$g, (int)$b];
    }

    /**
     * Handle file upload utility
     */
    public function handleImageUpload($image, $directory, $tableId)
    {
        if (!$image) return null;

        $imageName = 'table_' . $tableId . '_' . time() . '.' . $image->getClientOriginalExtension();

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $image->move($directory, $imageName);
        return $imageName;
    }
}
