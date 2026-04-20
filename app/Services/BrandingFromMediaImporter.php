<?php

namespace App\Services;

use App\Models\MediaAsset;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class BrandingFromMediaImporter
{
    /**
     * Copy an image from the private media library to the public branding folder.
     *
     * @return non-empty-string|null Relative path on the public disk, or null on failure.
     */
    public static function copyToPublicBranding(MediaAsset $asset): ?string
    {
        if (! $asset->isImage() || ! $asset->pathIsAllowed()) {
            return null;
        }

        $mime = (string) $asset->mime_type;
        if (str_contains($mime, 'svg')) {
            return null;
        }

        $src = Storage::disk($asset->disk);
        if (! $src->exists($asset->path)) {
            return null;
        }

        $binary = $src->get($asset->path);
        if ($binary === null || $binary === '') {
            return null;
        }

        $ext = strtolower((string) pathinfo($asset->original_name, PATHINFO_EXTENSION));
        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
            $ext = match ($mime) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
                default => 'png',
            };
        }

        $newPath = 'branding/'.(string) Str::uuid().'.'.$ext;
        Storage::disk('public')->put($newPath, $binary);

        return $newPath;
    }
}
