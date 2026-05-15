<?php

namespace App\Support;

use App\Models\ApplicationSetting;
use Illuminate\Support\Facades\Storage;

final class LegalDocument
{
    /**
     * @return array{key: string, label: string, filename: string}|null
     */
    public static function waiverPdf(string $audience, string $key): ?array
    {
        if (! in_array($audience, ['host', 'user'], true)) {
            return null;
        }

        $config = config("legal.{$audience}_waiver_pdfs.{$key}");

        if (! is_array($config) || empty($config['filename'])) {
            return null;
        }

        return [
            'key' => $key,
            'label' => (string) ($config['label'] ?? $key),
            'filename' => (string) $config['filename'],
        ];
    }

    /**
     * @return array{label: string, url: string, download_name: string, source: 'admin'|'public'}|null
     */
    public static function waiverPdfResolved(string $audience, string $key): ?array
    {
        $doc = self::waiverPdf($audience, $key);
        if ($doc === null) {
            return null;
        }

        $pathColumn = config("legal.{$audience}_waiver_path_columns.{$key}");
        $storedPath = $pathColumn
            ? ApplicationSetting::instance()->{$pathColumn}
            : null;

        if (filled($storedPath) && Storage::disk('public')->exists($storedPath)) {
            return [
                'label' => $doc['label'],
                'url' => Storage::disk('public')->url($storedPath),
                'download_name' => basename($storedPath),
                'source' => 'admin',
            ];
        }

        if (self::publicDocumentExists($audience, $doc['filename'])) {
            return [
                'label' => $doc['label'],
                'url' => self::publicDocumentUrl($audience, $doc['filename']),
                'download_name' => $doc['filename'],
                'source' => 'public',
            ];
        }

        return null;
    }

    /** @deprecated Use {@see waiverPdf()} with audience "host" */
    public static function hostWaiverPdf(string $key): ?array
    {
        return self::waiverPdf('host', $key);
    }

    /** @deprecated Use {@see waiverPdfResolved()} with audience "host" */
    public static function hostWaiverPdfResolved(string $key): ?array
    {
        return self::waiverPdfResolved('host', $key);
    }

    public static function publicDocumentRelativePath(string $audience, string $filename): string
    {
        $dir = trim((string) config("legal.{$audience}_document_directory", "documents/legal/{$audience}"), '/');

        return $dir.'/'.ltrim($filename, '/');
    }

    public static function publicDocumentExists(string $audience, string $filename): bool
    {
        $path = public_path(self::publicDocumentRelativePath($audience, $filename));

        return is_file($path) && is_readable($path);
    }

    public static function publicDocumentUrl(string $audience, string $filename): string
    {
        return asset(self::publicDocumentRelativePath($audience, $filename));
    }

    public static function waiverStoragePath(string $audience, string $key): string
    {
        $prefix = $audience === 'user' ? 'legal/user-waiver' : 'legal/host-waiver';
        $filename = match ($audience.':'.$key) {
            'host:nda' => 'host-nda.pdf',
            'host:contractor' => 'host-independent-contractor-agreement.pdf',
            'user:nda' => 'user-nda.pdf',
            'user:non_compete' => 'user-non-compete.pdf',
            default => preg_replace('/[^a-z0-9_-]/', '', $key).'.pdf',
        };

        return $prefix.'/'.$filename;
    }

    /**
     * @return list<string>
     */
    public static function waiverPdfKeys(string $audience): array
    {
        $pdfs = config("legal.{$audience}_waiver_pdfs", []);

        return is_array($pdfs) ? array_keys($pdfs) : [];
    }
}
