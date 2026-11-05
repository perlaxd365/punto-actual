<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;
use RuntimeException;

class CloudinaryImageService
{
    public const ROOT = 'punto-actual';

    public const FOLDER_NEWS = self::ROOT.'/noticias';

    public const FOLDER_ENTITIES = self::ROOT.'/entidades';

    public const FOLDER_VACANCIES = self::ROOT.'/convocatorias';

    protected ?Cloudinary $client = null;

    protected function client(): Cloudinary
    {
        if ($this->client instanceof Cloudinary) {
            return $this->client;
        }

        $cloudName = config('services.cloudinary.cloud_name');
        $apiKey = config('services.cloudinary.api_key');
        $apiSecret = config('services.cloudinary.api_secret');

        if (! $cloudName || ! $apiKey || ! $apiSecret) {
            throw new RuntimeException('Cloudinary no está configurado (CLOUDINARY_*).');
        }

        return $this->client = new Cloudinary([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
            ],
            'url' => [
                'secure' => (bool) config('services.cloudinary.secure', true),
            ],
        ]);
    }

    /**
     * @return array{public_id: string, url: string, secure_url: string, file_name: string, format: ?string, size: ?int, width: ?int, height: ?int}
     */
    public function upload(UploadedFile $file, string $folder): array
    {
        $response = $this->client()->uploadApi()->upload(
            $file->getRealPath(),
            [
                'folder' => $folder,
                'resource_type' => 'image',
                'unique_filename' => true,
                'use_filename' => true,
                'overwrite' => false,
            ]
        );

        return [
            'public_id' => (string) $response['public_id'],
            'url' => (string) $response['url'],
            'secure_url' => (string) $response['secure_url'],
            'file_name' => $file->getClientOriginalName(),
            'format' => $response['format'] ?? null,
            'size' => isset($response['bytes']) ? (int) $response['bytes'] : null,
            'width' => isset($response['width']) ? (int) $response['width'] : null,
            'height' => isset($response['height']) ? (int) $response['height'] : null,
        ];
    }

    public function delete(?string $publicId): void
    {
        if (! $publicId) {
            return;
        }

        try {
            $this->client()->adminApi()->deleteAssets($publicId, ['resource_type' => 'image']);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function isCloudinaryUrl(?string $url): bool
    {
        return $url !== null && str_contains($url, 'res.cloudinary.com');
    }

    public function publicIdFromUrl(?string $url): ?string
    {
        if (! $this->isCloudinaryUrl($url)) {
            return null;
        }

        $path = parse_url($url, PHP_URL_PATH);

        if (! $path) {
            return null;
        }

        $parts = explode('/', trim($path, '/'));
        $uploadIndex = array_search('upload', $parts, true);

        if ($uploadIndex === false) {
            return null;
        }

        $publicIdParts = array_slice($parts, $uploadIndex + 1);

        if (isset($publicIdParts[0]) && preg_match('/^v\d+$/', $publicIdParts[0])) {
            array_shift($publicIdParts);
        }

        if (empty($publicIdParts)) {
            return null;
        }

        $last = end($publicIdParts);
        if ($last !== false) {
            $publicIdParts[count($publicIdParts) - 1] = preg_replace(
                '/\.(jpg|jpeg|png|gif|webp|avif|svg|bmp|ico)$/i',
                '',
                $last
            );
        }

        $publicId = implode('/', $publicIdParts);

        return $publicId !== '' ? $publicId : null;
    }
}
