<?php

namespace App\Services\Blog;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class BlogFeaturedImageStorage
{
    public const MAX_BYTES = 1048576;

    /**
     * @throws InvalidArgumentException
     */
    public function assertValidImage(UploadedFile $file): void
    {
        if (! $file->isValid()) {
            throw new InvalidArgumentException('Invalid upload.');
        }

        if ($file->getSize() > self::MAX_BYTES) {
            throw new InvalidArgumentException('Image exceeds size limit.');
        }

        $path = $file->getRealPath();
        if ($path === false) {
            throw new InvalidArgumentException('Invalid upload.');
        }

        $info = @getimagesize($path);
        if ($info === false) {
            throw new InvalidArgumentException('Invalid image file.');
        }

        if (! in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
            throw new InvalidArgumentException('Only JPG and PNG images are allowed.');
        }

        $detectedMime = $info['mime'] ?? '';
        if (! in_array($detectedMime, ['image/jpeg', 'image/png'], true)) {
            throw new InvalidArgumentException('Only JPG and PNG images are allowed.');
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function store(UploadedFile $file, int $postId): string
    {
        $this->assertValidImage($file);

        $path = $file->getRealPath();
        $info = @getimagesize($path !== false ? $path : '');
        $extension = ($info[2] ?? null) === IMAGETYPE_PNG ? 'png' : 'jpg';
        $filename = Str::random(40).'.'.$extension;
        $directory = 'blog-posts/'.$postId;

        Storage::disk('public')->putFileAs($directory, $file, $filename);

        return $directory.'/'.$filename;
    }
}
