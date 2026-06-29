<?php

namespace App\Support;

final class GymBrowseCatalog
{
    /**
     * Host offering model: private, semi-private, or open.
     *
     * @return list<array{key: string, label: string, description: string, example: string, icon: string}>
     */
    public static function serviceTypes(): array
    {
        $types = config('gym_listing.service_types', []);
        $out = [];

        foreach ($types as $key => $meta) {
            if (! is_array($meta)) {
                continue;
            }
            $out[] = [
                'key' => (string) $key,
                'label' => (string) ($meta['label'] ?? $key),
                'description' => (string) ($meta['description'] ?? ''),
                'example' => (string) ($meta['example'] ?? ''),
                'icon' => (string) ($meta['icon'] ?? 'fa-circle'),
            ];
        }

        return $out;
    }

    /**
     * Activity shortcuts shown on find-a-gym browse pages.
     *
     * @return list<array{key: string, label: string, icon_path: string}>
     */
    public static function browseActivities(): array
    {
        $activities = config('gym_listing.browse_activities', []);
        $out = [];

        foreach ($activities as $key => $meta) {
            if (! is_array($meta)) {
                continue;
            }
            $icon = (string) ($meta['icon'] ?? '');
            $out[] = [
                'key' => (string) $key,
                'label' => (string) ($meta['label'] ?? $key),
                'icon_path' => $icon !== '' ? asset($icon) : '',
            ];
        }

        return $out;
    }

    /**
     * @return array<string, list<string>>
     */
    public static function activityFilterAliases(): array
    {
        return (array) config('gym_listing.browse_activity_aliases', []);
    }

    public static function serviceTypeLabel(string $key): string
    {
        $meta = config('gym_listing.service_types.'.$key);

        return is_array($meta) ? (string) ($meta['label'] ?? $key) : $key;
    }
}
