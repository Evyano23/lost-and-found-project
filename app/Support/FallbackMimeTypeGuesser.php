<?php

namespace App\Support;

use Symfony\Component\Mime\MimeTypeGuesserInterface;

/**
 * Extension-based MIME type guesser that works without the php_fileinfo extension.
 * Registered in AppServiceProvider as the first guesser so it runs before
 * FinfoMimeTypeGuesser (which requires the blocked fileinfo DLL on this host).
 */
class FallbackMimeTypeGuesser implements MimeTypeGuesserInterface
{
    /**
     * Common image/document extension → MIME map.
     */
    private const MAP = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'bmp' => 'image/bmp',
        'ico' => 'image/x-icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'pdf' => 'application/pdf',
        'txt' => 'text/plain',
        'csv' => 'text/csv',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'zip' => 'application/zip',
        'mp4' => 'video/mp4',
        'mp3' => 'audio/mpeg',
    ];

    public function isGuesserSupported(): bool
    {
        return true;
    }

    public function guessMimeType(string $path): ?string
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return self::MAP[$ext] ?? 'application/octet-stream';
    }
}
