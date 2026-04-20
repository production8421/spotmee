<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function pathIsAllowed(): bool
    {
        $path = (string) $this->path;

        return $path !== ''
            && str_starts_with($path, 'media/')
            && ! str_contains($path, '..');
    }

    public function streamInlineResponse(): StreamedResponse
    {
        if (! $this->pathIsAllowed()) {
            abort(404);
        }

        $disk = Storage::disk($this->disk);

        if (! $disk->exists($this->path)) {
            abort(404);
        }

        return $disk->response(
            $this->path,
            $this->safeAsciiFilename(),
            [
                'Content-Type' => $this->mime_type,
                'X-Content-Type-Options' => 'nosniff',
            ],
            'inline',
        );
    }

    public function safeAsciiFilename(): string
    {
        $name = basename((string) $this->original_name);
        $ascii = preg_replace('/[^\x20-\x7E]/', '_', $name) ?? 'file';

        return $ascii !== '' ? $ascii : 'file';
    }
}
