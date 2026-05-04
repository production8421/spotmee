<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class MediaAsset extends Model
{
    protected $fillable = [
        'user_id',
        'disk',
        'path',
        'original_name',
        'mime_type',
        'size',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isImage(): bool
    {
        return str_starts_with((string) $this->mime_type, 'image/');
    }

    public function isVideo(): bool
    {
        return str_starts_with((string) $this->mime_type, 'video/');
    }

    /**
     * Relative path inside the disk root, always with forward slashes.
     */
    public function normalizedRelativePath(): string
    {
        return str_replace('\\', '/', (string) $this->path);
    }

    public function pathIsAllowed(): bool
    {
        $path = $this->normalizedRelativePath();

        return $path !== ''
            && str_starts_with($path, 'media/')
            && ! str_contains($path, '..');
    }

    /**
     * Stream or send the file for inline display (images/videos in admin UI).
     *
     * Resolves files from the recorded disk first. If the disk is `local` (root:
     * storage/app/private) but the file is missing, checks legacy locations where
     * uploads may still exist: storage/app/{path} (pre-private layout) and the
     * `public` disk (storage/app/public), so media library thumbnails keep working
     * after filesystem config changes.
     */
    public function streamInlineResponse(): Response
    {
        if (! $this->pathIsAllowed()) {
            abort(404);
        }

        $rel = $this->normalizedRelativePath();

        $headers = [
            'Content-Type' => (string) $this->mime_type,
            'X-Content-Type-Options' => 'nosniff',
        ];

        $disk = Storage::disk($this->disk);
        if ($disk->exists($rel)) {
            return $disk->response($rel, $this->safeAsciiFilename(), $headers, 'inline');
        }

        if ($this->disk === 'local') {
            $legacyUnderApp = storage_path('app/'.$rel);
            if (is_file($legacyUnderApp)) {
                return response()->file($legacyUnderApp, $headers);
            }

            $public = Storage::disk('public');
            if ($public->exists($rel)) {
                return $public->response($rel, $this->safeAsciiFilename(), $headers, 'inline');
            }
        }

        abort(404);
    }

    /**
     * Absolute path to the file on disk if it exists (any supported location), or null.
     */
    public function resolveAbsolutePath(): ?string
    {
        if (! $this->pathIsAllowed()) {
            return null;
        }

        $rel = $this->normalizedRelativePath();

        $disk = Storage::disk($this->disk);
        if ($disk->exists($rel)) {
            return $disk->path($rel);
        }

        if ($this->disk === 'local') {
            $legacyUnderApp = storage_path('app/'.$rel);
            if (is_file($legacyUnderApp)) {
                return $legacyUnderApp;
            }

            $public = Storage::disk('public');
            if ($public->exists($rel)) {
                return $public->path($rel);
            }
        }

        return null;
    }

    public function safeAsciiFilename(): string
    {
        $name = basename((string) $this->original_name);
        $ascii = preg_replace('/[^\x20-\x7E]/', '_', $name) ?? 'file';

        return $ascii !== '' ? $ascii : 'file';
    }

    /**
     * Remove the binary from every known storage location (primary + fallbacks).
     */
    public function deleteStoredFile(): void
    {
        if (! $this->pathIsAllowed()) {
            return;
        }

        $rel = $this->normalizedRelativePath();

        Storage::disk($this->disk)->delete($rel);

        if ($this->disk === 'local') {
            $legacyUnderApp = storage_path('app/'.$rel);
            if (is_file($legacyUnderApp)) {
                @unlink($legacyUnderApp);
            }
            Storage::disk('public')->delete($rel);
        }
    }
}
