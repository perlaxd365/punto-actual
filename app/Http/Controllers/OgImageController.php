<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class OgImageController extends Controller
{
    private const WIDTH = 1200;

    private const HEIGHT = 630;

    private const VERSION = 5;

    public function news(Request $request, News $news)
    {
        return $this->render(
            'news',
            $news->id,
            $news->image,
            $news->title,
            $news->updated_at?->timestamp ?? time(),
            $this->isWhatsApp($request)
        );
    }

    public function vacancy(Request $request, Vacancy $vacancy)
    {
        return $this->render(
            'vacancy',
            $vacancy->id,
            $vacancy->image ?: $vacancy->entity?->logo,
            $vacancy->title,
            $vacancy->updated_at?->timestamp ?? time(),
            $this->isWhatsApp($request)
        );
    }

    private function isWhatsApp(Request $request): bool
    {
        return stripos((string) $request->userAgent(), 'whatsapp') !== false;
    }

    private function render(string $type, int $id, ?string $imageUrl, string $title, int $version, bool $soft)
    {
        $variant = $soft ? 'whatsapp' : 'standard';
        $hash = substr(md5($type.'|'.$id.'|'.($imageUrl ?? 'none').'|'.$title.'|'.$version.'|'.self::VERSION.'|'.$variant), 0, 10);
        $dir = storage_path('app/public/og');

        if (! is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        $path = $dir.'/'.$type.'-'.$id.'-'.$hash.'.jpg';

        if (! is_file($path)) {
            $canvas = $this->build($imageUrl, $soft);
            imagejpeg($canvas, $path, 86);
            imagedestroy($canvas);
        }

        return response()->file($path, [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }

    private function build(?string $imageUrl, bool $soft)
    {
        $canvas = imagecreatetruecolor(self::WIDTH, self::HEIGHT);
        imagealphablending($canvas, true);

        $dark = imagecolorallocate($canvas, 10, 10, 10);
        imagefilledrectangle($canvas, 0, 0, self::WIDTH, self::HEIGHT, $dark);

        if ($imageUrl) {
            $source = $this->fetchImage($imageUrl);

            if ($source !== null) {
                $this->cover($canvas, $source);
                imagedestroy($source);
            }
        }

        $this->bottomGradient($canvas);
        $this->watermark($canvas, $soft);

        return $canvas;
    }

    private function fetchImage(string $url): mixed
    {
        $context = stream_context_create([
            'http' => [
                'timeout' => 12,
                'user_agent' => 'Mozilla/5.0 (compatible; PuntoActualBot/1.0)',
                'follow_location' => 1,
            ],
            'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
        ]);

        $binary = @file_get_contents($url, false, $context);

        if ($binary === false) {
            return null;
        }

        $image = @imagecreatefromstring($binary);

        return $image !== false ? $image : null;
    }

    private function cover($canvas, $source): void
    {
        $srcW = imagesx($source);
        $srcH = imagesy($source);

        if ($srcW < 1 || $srcH < 1) {
            return;
        }

        $scale = max(self::WIDTH / $srcW, self::HEIGHT / $srcH);
        $newW = (int) ceil($srcW * $scale);
        $newH = (int) ceil($srcH * $scale);
        $dstX = (int) floor((self::WIDTH - $newW) / 2);
        $dstY = (int) floor((self::HEIGHT - $newH) / 2);

        imagecopyresampled($canvas, $source, $dstX, $dstY, 0, 0, $newW, $newH, $srcW, $srcH);
    }

    private function bottomGradient($canvas): void
    {
        $start = 470;
        $steps = self::HEIGHT - $start;

        for ($i = 0; $i < $steps; $i++) {
            $alpha = (int) (78 - (($i / $steps) * 50));
            $color = imagecolorallocatealpha($canvas, 0, 0, 0, max(0, min(120, $alpha)));
            imagefilledrectangle($canvas, 0, $start + $i, self::WIDTH, $start + $i + 1, $color);
        }
    }

    private function watermark($canvas, bool $soft): void
    {
        $path = public_path('images/logo-punto-actual-watermark.png');

        if (! is_file($path)) {
            return;
        }

        $logo = @imagecreatefrompng($path);

        if ($logo === false) {
            return;
        }

        $targetH = 64;
        $targetW = (int) round(imagesx($logo) * ($targetH / imagesy($logo)));
        $pad = 34;

        $scaled = imagecreatetruecolor($targetW, $targetH);
        imagealphablending($scaled, false);
        imagesavealpha($scaled, true);
        $transparent = imagecolorallocatealpha($scaled, 0, 0, 0, 127);
        imagefilledrectangle($scaled, 0, 0, $targetW, $targetH, $transparent);
        imagealphablending($scaled, true);
        imagecopyresampled($scaled, $logo, 0, 0, 0, 0, $targetW, $targetH, imagesx($logo), imagesy($logo));
        imagedestroy($logo);

        if ($soft) {
            $scaled = $this->blurWatermark($scaled, $targetW, $targetH);
            $this->reduceOpacity($scaled, 0.5);
        }

        imagecopy(
            $canvas,
            $scaled,
            self::WIDTH - $targetW - $pad,
            self::HEIGHT - $targetH - $pad,
            0,
            0,
            $targetW,
            $targetH
        );

        imagedestroy($scaled);
    }

    private function blurWatermark($image, int $width, int $height)
    {
        $tinyW = max(1, (int) round($width / 6));
        $tinyH = max(1, (int) round($height / 6));

        $tiny = imagecreatetruecolor($tinyW, $tinyH);
        imagealphablending($tiny, false);
        imagesavealpha($tiny, true);
        imagefilledrectangle($tiny, 0, 0, $tinyW, $tinyH, imagecolorallocatealpha($tiny, 0, 0, 0, 127));
        imagealphablending($tiny, true);
        imagecopyresampled($tiny, $image, 0, 0, 0, 0, $tinyW, $tinyH, $width, $height);

        $soft = imagecreatetruecolor($width, $height);
        imagealphablending($soft, false);
        imagesavealpha($soft, true);
        imagefilledrectangle($soft, 0, 0, $width, $height, imagecolorallocatealpha($soft, 0, 0, 0, 127));
        imagealphablending($soft, true);
        imagecopyresampled($soft, $tiny, 0, 0, 0, 0, $width, $height, $tinyW, $tinyH);

        imagedestroy($tiny);
        imagedestroy($image);

        return $soft;
    }

    private function reduceOpacity($image, float $opacity): void
    {
        $width = imagesx($image);
        $height = imagesy($image);

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $color = imagecolorat($image, $x, $y);
                $alpha = ($color >> 24) & 0x7F;
                $newAlpha = 127 - (int) round((127 - $alpha) * $opacity);
                $rgb = $color & 0xFFFFFF;
                imagesetpixel($image, $x, $y, ($newAlpha << 24) | $rgb);
            }
        }
    }
}
