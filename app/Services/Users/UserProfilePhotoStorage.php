<?php

namespace App\Services\Users;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

final class UserProfilePhotoStorage
{
    public const MAX_BYTES = 5242880;

    /**
     * @throws InvalidArgumentException
     */
    public function assertValidImage(UploadedFile $file): void
    {
        if (! $file->isValid()) {
            throw new InvalidArgumentException(__('Invalid upload.'));
        }

        if ($file->getSize() > self::MAX_BYTES) {
            throw new InvalidArgumentException(__('Profile photo exceeds the 5 MB size limit.'));
        }

        $path = $file->getRealPath();
        if ($path === false) {
            throw new InvalidArgumentException(__('Invalid upload.'));
        }

        $info = @getimagesize($path);
        if ($info === false) {
            throw new InvalidArgumentException(__('Invalid image file.'));
        }

        if (! in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            throw new InvalidArgumentException(__('Only JPG, PNG, and WebP images are allowed.'));
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function storeForUser(UploadedFile $file, int $userId): string
    {
        return $this->store($file, 'users/'.$userId);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function storeForHostApplication(UploadedFile $file, int $applicationId): string
    {
        return $this->store($file, 'host-applications/'.$applicationId);
    }

    public function delete(?string $path): void
    {
        if (! is_string($path) || trim($path) === '') {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    public function copyToUser(string $sourcePath, int $userId): ?string
    {
        $disk = Storage::disk('public');
        if (! $disk->exists($sourcePath)) {
            return null;
        }

        $extension = pathinfo($sourcePath, PATHINFO_EXTENSION) ?: 'jpg';
        $destination = 'users/'.$userId.'/'.Str::random(40).'.'.$extension;
        $disk->copy($sourcePath, $destination);

        return $destination;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function store(UploadedFile $file, string $directory): string
    {
        $this->assertValidImage($file);

        $path = $file->getRealPath();
        $info = @getimagesize($path !== false ? $path : '');
        $extension = match ($info[2] ?? null) {
            IMAGETYPE_PNG => 'png',
            IMAGETYPE_WEBP => 'webp',
            default => 'jpg',
        };
        $filename = 'profile-'.Str::random(24).'.'.$extension;

        Storage::disk('public')->putFileAs($directory, $file, $filename);

        return $directory.'/'.$filename;
    }
}
