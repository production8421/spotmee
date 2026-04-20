<?php

namespace App\Services;

use App\Models\MediaAsset;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class FrontendHeroMediaImporter
{
    /**
     * Copy a raster image or allowed video from the private media library to public storage.
     *
     * @param  'image'|'video'  $expectedKind
     * @return non-empty-string|null Relative path on the public disk, or null on failure.
     */
    public static function copyToPublic(MediaAsset $asset, string $expectedKind): ?string
    {
        if (! $asset->pathIsAllowed()) {
            return null;
        }

        if ($expectedKind === 'image') {
            if (! $asset->isImage()) {
                return null;
            }
            $mime = (string) $asset->mime_type;
            if (str_contains($mime, 'svg')) {
                return null;
            }
        } elseif ($expectedKind === 'video') {
            if (! $asset->isVideo()) {
                return null;
            }
            $mime = (string) $asset->mime_type;
            if (! in_array($mime, ['video/mp4', 'video/webm', 'video/quicktime'], true)) {
                return null;
            }
        } else {
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

        if ($expectedKind === 'image') {
            $mime = (string) $asset->mime_type;
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
            $newPath = 'frontend/home/'.(string) Str::uuid().'.'.$ext;
        } else {
            $ext = strtolower((string) pathinfo($asset->original_name, PATHINFO_EXTENSION));
            if (! in_array($ext, ['mp4', 'webm', 'mov'], true)) {
                $ext = match ((string) $asset->mime_type) {
                    'video/webm' => 'webm',
                    'video/quicktime' => 'mov',
                    default => 'mp4',
                };
            }
            $newPath = 'frontend/home/'.(string) Str::uuid().'.'.$ext;
        }

        Storage::disk('public')->put($newPath, $binary);

        return $newPath;
    }
}
