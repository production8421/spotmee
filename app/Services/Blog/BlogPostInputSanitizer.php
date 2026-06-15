<?php

namespace App\Services\Blog;

use Illuminate\Support\Str;

class BlogPostInputSanitizer
{
    public const MAX_BODY_LENGTH = 50000;

    public function sanitizeTitle(string $title): string
    {
        $title = $this->removeNullBytes($title);
        $title = strip_tags($title);
        $title = html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $title = trim(preg_replace('/\s+/u', ' ', $title) ?? $title);

        return Str::limit($title, 200, '');
    }

    public function sanitizeBody(string $html): string
    {
        $html = $this->removeNullBytes($html);

        if (strlen($html) > self::MAX_BODY_LENGTH) {
            $html = substr($html, 0, self::MAX_BODY_LENGTH);
        }

        $dangerousBlocks = [
            '#<\s*(script|iframe|object|embed|form|meta|link|base|svg|math|video|audio)\b[^>]*>.*?</\s*\1\s*>#is',
            '#<\s*(script|iframe|object|embed|form|meta|link|base|input|button|textarea|select|video|audio)\b[^>]*/?\s*>#is',
        ];

        foreach ($dangerousBlocks as $pattern) {
            $html = preg_replace($pattern, '', $html) ?? $html;
        }

        $allowed = '<p><br><strong><b><em><i><u><s><strike><sub><sup><ul><ol><li><h2><h3><h4><blockquote><pre><hr><table><thead><tbody><tr><th><td><a><span>';
        $html = strip_tags($html, $allowed);

        $html = preg_replace_callback(
            '/\s(href|src)\s*=\s*("|\')?\s*(javascript|vbscript|data):[^"\'>\s]*/i',
            static fn (): string => '',
            $html
        ) ?? $html;

        $html = preg_replace('/\s(on\w+|formaction|xmlns)\s*=\s*("|\')[^"\']*("|\')/i', '', $html) ?? $html;
        $html = preg_replace('/\s(on\w+|formaction|xmlns)\s*=\s*[^\s>]+/i', '', $html) ?? $html;

        $html = preg_replace_callback(
            '/\sstyle\s*=\s*("|\')([^"\']*)\1/i',
            function (array $matches): string {
                $style = $matches[2];
                if (preg_match('/expression|javascript|url\s*\(\s*["\']?\s*javascript/i', $style)) {
                    return '';
                }

                return ' style="'.e($style).'"';
            },
            $html
        ) ?? $html;

        return trim($html);
    }

    public function sanitizeComment(string $body): string
    {
        $body = $this->removeNullBytes($body);
        $body = strip_tags($body);
        $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $body = trim(preg_replace('/\s+/u', ' ', $body) ?? $body);

        return Str::limit($body, 5000, '');
    }

    private function removeNullBytes(string $value): string
    {
        return str_replace("\0", '', $value);
    }
}
