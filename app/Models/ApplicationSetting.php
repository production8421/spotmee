<?php

namespace App\Models;

use App\Services\Mail\SiteEmailTemplateService;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

#[Fillable([
    'header_logo_path',
    'footer_logo_path',
    'stripe_mode',
    'stripe_test_publishable_key',
    'stripe_test_secret_key',
    'stripe_live_publishable_key',
    'stripe_live_secret_key',
    'silver_tier_price_1_hour',
    'silver_tier_price_40_min',
    'silver_tier_admin_commission_1_hour_pct',
    'silver_tier_admin_commission_40_min_pct',
    'gold_tier_price_1_hour',
    'gold_tier_price_40_min',
    'gold_tier_admin_commission_1_hour_pct',
    'gold_tier_admin_commission_40_min_pct',
    'platinum_tier_price_1_hour',
    'platinum_tier_price_40_min',
    'platinum_tier_admin_commission_1_hour_pct',
    'platinum_tier_admin_commission_40_min_pct',
    'pt_silver_price_per_slot',
    'pt_silver_admin_commission_pct',
    'pt_gold_price_per_slot',
    'pt_gold_admin_commission_pct',
    'pt_platinum_price_per_slot',
    'pt_platinum_admin_commission_pct',
    'legal_booking_terms_url',
    'legal_booking_privacy_url',
    'legal_host_terms_url',
    'legal_host_privacy_url',
    'booking_cancel_result_url',
    'legal_host_registration_url',
    'home_hero_heading',
    'home_hero_background_type',
    'home_hero_background_color',
    'home_hero_background_path',
    'home_hero_button1_label',
    'home_hero_button1_url',
    'home_hero_button2_label',
    'home_hero_button2_url',
    'home_how_section',
    'home_why_section',
    'home_earn_section',
    'home_community_section',
    'home_promo_banner_section',
    'how_it_works_hero_title',
    'how_it_works_hero_background_color',
    'how_it_works_intro_section',
    'how_it_works_approach_section',
    'find_a_gym_hero_title',
    'find_a_gym_hero_background_color',
    'become_a_host_hero_title',
    'become_a_host_hero_background_color',
    'faq_hero_title',
    'faq_hero_background_color',
    'faq_page_items',
    'contact_hero_title',
    'contact_hero_background_color',
    'waiver_liability_host_hero_title',
    'waiver_liability_host_hero_background_color',
    'waiver_liability_user_hero_title',
    'waiver_liability_user_hero_background_color',
    'cancellation_policy_hero_title',
    'cancellation_policy_hero_background_color',
    'footer_social_urls',
    'webhook_booking_completed_url',
    'webhook_booking_completed_secret',
    'webhook_booking_cancelled_url',
    'webhook_booking_cancelled_secret',
    'notification_email_templates',
    'smtp_enabled',
    'smtp_host',
    'smtp_port',
    'smtp_encryption',
    'smtp_username',
    'smtp_password',
    'smtp_from_address',
    'smtp_from_name',
    'host_registration_auto_approve',
])]
class ApplicationSetting extends Model
{
    public static function instance(): self
    {
        $table = (new static)->getTable();
        if (! Schema::hasTable($table)) {
            return new static;
        }

        $row = static::query()->first();
        if ($row === null) {
            return static::query()->create([]);
        }

        return $row;
    }

    public function headerLogoUrl(): ?string
    {
        return $this->header_logo_path
            ? asset('storage/'.$this->header_logo_path)
            : null;
    }

    public function footerLogoUrl(): ?string
    {
        return $this->footer_logo_path
            ? asset('storage/'.$this->footer_logo_path)
            : null;
    }

    public function homeHeroBackgroundPublicUrl(): ?string
    {
        return filled($this->home_hero_background_path)
            ? asset('storage/'.$this->home_hero_background_path)
            : null;
    }

    public function homeHeroHeadingDisplay(): string
    {
        if (filled($this->home_hero_heading)) {
            return (string) $this->home_hero_heading;
        }

        return __('Let\'s get started');
    }

    /**
     * @return 'color'|'image'|'video'
     */
    public function homeHeroBackgroundKind(): string
    {
        $t = (string) ($this->home_hero_background_type ?? 'color');

        return in_array($t, ['color', 'image', 'video'], true) ? $t : 'color';
    }

    public function homeHeroSolidColorCss(): string
    {
        $c = (string) ($this->home_hero_background_color ?? '');

        return preg_match('/^#[0-9A-Fa-f]{6}$/', $c) === 1 ? $c : '#e3e3e0';
    }

    public function howItWorksHeroTitleDisplay(): string
    {
        if (filled($this->how_it_works_hero_title)) {
            return (string) $this->how_it_works_hero_title;
        }

        return __('How It Works');
    }

    public function howItWorksHeroBackgroundColorCss(): string
    {
        $c = (string) ($this->how_it_works_hero_background_color ?? '');

        return preg_match('/^#[0-9A-Fa-f]{6}$/', $c) === 1 ? $c : '#2563eb';
    }

    /**
     * @param  'find_a_gym'|'become_a_host'|'faq'|'contact'|'waiver_liability_host'|'waiver_liability_user'|'cancellation_policy'  $prefix
     */
    public function frontendPageHeroTitleDisplay(string $prefix): string
    {
        $attr = "{$prefix}_hero_title";
        if (filled($this->{$attr})) {
            return (string) $this->{$attr};
        }

        return match ($prefix) {
            'find_a_gym' => __('Find a Gym'),
            'become_a_host' => __('Become a Host'),
            'faq' => __('FAQ'),
            'contact' => __('Contact'),
            'waiver_liability_host' => __('Waiver of Liability Host'),
            'waiver_liability_user' => __('Waiver of Liability User'),
            'cancellation_policy' => __('Cancellation Policy'),
            default => '',
        };
    }

    /**
     * @param  'find_a_gym'|'become_a_host'|'faq'|'contact'|'waiver_liability_host'|'waiver_liability_user'|'cancellation_policy'  $prefix
     */
    public function frontendPageHeroBackgroundColorCss(string $prefix): string
    {
        $attr = "{$prefix}_hero_background_color";
        $c = (string) ($this->{$attr} ?? '');

        return preg_match('/^#[0-9A-Fa-f]{6}$/', $c) === 1 ? $c : '#2563eb';
    }

    /**
     * @return list<string>
     */
    public static function footerSocialPlatformKeys(): array
    {
        return ['instagram', 'facebook', 'snapchat', 'linkedin', 'tiktok'];
    }

    /**
     * @return array<string, string>
     */
    public static function normalizeFooterSocialUrlsForForm(mixed $raw): array
    {
        $defaults = array_fill_keys(self::footerSocialPlatformKeys(), '');
        if (! is_array($raw)) {
            return $defaults;
        }
        foreach (self::footerSocialPlatformKeys() as $key) {
            if (isset($raw[$key])) {
                $defaults[$key] = trim((string) $raw[$key]);
            }
        }

        return $defaults;
    }

    /**
     * @param  array<string, mixed>  $input
     */
    public static function normalizeFooterSocialUrlsForStorage(array $input): ?array
    {
        $out = [];
        foreach (self::footerSocialPlatformKeys() as $key) {
            $raw = isset($input[$key]) ? trim((string) $input[$key]) : '';
            if ($raw === '') {
                $out[$key] = '';

                continue;
            }
            $href = self::normalizeHeroPublicUrl($raw);
            $out[$key] = $href !== null ? $href : '';
        }

        $hasAny = collect($out)->contains(fn (string $v): bool => $v !== '');

        return $hasAny ? $out : null;
    }

    /**
     * @return list<array{platform: string, href: string, label: string}>
     */
    public function footerSocialLinksForPublic(): array
    {
        $labels = [
            'instagram' => __('Instagram'),
            'facebook' => __('Facebook'),
            'snapchat' => __('Snapchat'),
            'linkedin' => __('LinkedIn'),
            'tiktok' => __('TikTok'),
        ];
        $m = self::normalizeFooterSocialUrlsForForm($this->footer_social_urls);
        $items = [];
        foreach ($m as $platform => $url) {
            if ($url === '') {
                continue;
            }
            $href = self::normalizeHeroPublicUrl($url);
            if ($href !== null) {
                $items[] = [
                    'platform' => $platform,
                    'href' => $href,
                    'label' => $labels[$platform] ?? $platform,
                ];
            }
        }

        return $items;
    }

    /**
     * FAQ rows for the admin form (at least one blank row when nothing is stored).
     *
     * @return list<array{question: string, answer: string}>
     */
    public static function normalizeFaqPageItemsForForm(mixed $raw): array
    {
        if (! is_array($raw) || $raw === []) {
            return [['question' => '', 'answer' => '']];
        }

        $out = [];
        foreach ($raw as $row) {
            if (! is_array($row)) {
                continue;
            }
            $out[] = [
                'question' => isset($row['question']) ? trim((string) $row['question']) : '',
                'answer' => isset($row['answer']) ? trim((string) $row['answer']) : '',
            ];
        }

        return $out !== [] ? $out : [['question' => '', 'answer' => '']];
    }

    /**
     * @return list<array{question: string, answer: string}>|null
     */
    public static function normalizeFaqPageItemsFromRequestInput(mixed $items): ?array
    {
        if (! is_array($items)) {
            return null;
        }

        $out = collect($items)
            ->filter(fn (mixed $row): bool => is_array($row))
            ->map(function (array $row): array {
                return [
                    'question' => trim((string) ($row['question'] ?? '')),
                    'answer' => trim((string) ($row['answer'] ?? '')),
                ];
            })
            ->filter(fn (array $row): bool => $row['question'] !== '' && $row['answer'] !== '')
            ->values()
            ->all();

        return $out !== [] ? $out : null;
    }

    /**
     * @return list<array{question: string, answer: string}>
     */
    public function faqPageItemsForPublic(): array
    {
        $raw = $this->faq_page_items;
        if (! is_array($raw)) {
            return [];
        }

        $out = [];
        foreach ($raw as $row) {
            if (! is_array($row)) {
                continue;
            }
            $q = isset($row['question']) ? trim((string) $row['question']) : '';
            $a = isset($row['answer']) ? trim((string) $row['answer']) : '';
            if ($q !== '' && $a !== '') {
                $out[] = ['question' => $q, 'answer' => $a];
            }
        }

        return $out;
    }

    /**
     * Normalized “How it works” page intro block (second section: title, subtitle, copy, two CTAs, optional image).
     *
     * @return array{heading: string, emphasis: string, subtitle: string, description_1: string, description_2: string, button1_label: string, button1_url: string, button2_label: string, button2_url: string, image_path: ?string}
     */
    public static function normalizeHowItWorksIntroSection(mixed $raw): array
    {
        $defaults = [
            'heading' => '',
            'emphasis' => '',
            'subtitle' => '',
            'description_1' => '',
            'description_2' => '',
            'button1_label' => '',
            'button1_url' => '',
            'button2_label' => '',
            'button2_url' => '',
            'image_path' => null,
        ];

        if (! is_array($raw)) {
            return $defaults;
        }

        $out = $defaults;
        $out['heading'] = isset($raw['heading']) ? trim((string) $raw['heading']) : '';
        $out['emphasis'] = isset($raw['emphasis']) ? trim((string) $raw['emphasis']) : '';
        $out['subtitle'] = isset($raw['subtitle']) ? trim((string) $raw['subtitle']) : '';
        $out['description_1'] = isset($raw['description_1']) ? trim((string) $raw['description_1']) : '';
        $out['description_2'] = isset($raw['description_2']) ? trim((string) $raw['description_2']) : '';
        $out['button1_label'] = isset($raw['button1_label']) ? trim((string) $raw['button1_label']) : '';
        $out['button1_url'] = isset($raw['button1_url']) ? trim((string) $raw['button1_url']) : '';
        $out['button2_label'] = isset($raw['button2_label']) ? trim((string) $raw['button2_label']) : '';
        $out['button2_url'] = isset($raw['button2_url']) ? trim((string) $raw['button2_url']) : '';

        $path = isset($raw['image_path']) && is_string($raw['image_path']) && $raw['image_path'] !== ''
            ? trim($raw['image_path'])
            : null;
        if ($path !== null && (str_contains($path, '..') || ! str_starts_with($path, 'frontend/how-it-works-page/intro/'))) {
            $path = null;
        }
        $out['image_path'] = $path;

        return $out;
    }

    /**
     * @return array{heading: string, emphasis: string, subtitle: string, description_1: string, description_2: string, cta1: ?array{label: string, href: string}, cta2: ?array{label: string, href: string}, image_url: ?string}|null
     */
    public function howItWorksIntroSectionForView(): ?array
    {
        $m = self::normalizeHowItWorksIntroSection($this->how_it_works_intro_section);

        $pair1 = $m['button1_label'] !== '' && $m['button1_url'] !== '';
        $pair2 = $m['button2_label'] !== '' && $m['button2_url'] !== '';

        $hasContent = $m['heading'] !== ''
            || $m['emphasis'] !== ''
            || $m['subtitle'] !== ''
            || $m['description_1'] !== ''
            || $m['description_2'] !== ''
            || $pair1
            || $pair2
            || filled($m['image_path']);

        if (! $hasContent) {
            return null;
        }

        $displayHeading = $m['heading'] !== '' ? $m['heading'] : __('How SPOTMEE works');

        $cta1 = null;
        if ($pair1) {
            $href = self::normalizeHeroPublicUrl($m['button1_url']);
            if ($href !== null) {
                $cta1 = ['label' => $m['button1_label'], 'href' => $href];
            }
        }

        $cta2 = null;
        if ($pair2) {
            $href = self::normalizeHeroPublicUrl($m['button2_url']);
            if ($href !== null) {
                $cta2 = ['label' => $m['button2_label'], 'href' => $href];
            }
        }

        $img = $m['image_path'];
        $imageUrl = filled($img) ? asset('storage/'.$img) : null;

        return [
            'heading' => $displayHeading,
            'emphasis' => $m['emphasis'],
            'subtitle' => $m['subtitle'],
            'description_1' => $m['description_1'],
            'description_2' => $m['description_2'],
            'cta1' => $cta1,
            'cta2' => $cta2,
            'image_url' => $imageUrl,
        ];
    }

    /**
     * Normalized “How it works” page third section (image left, title + description right).
     *
     * @return array{title: string, emphasis: string, description: string, image_path: ?string}
     */
    public static function normalizeHowItWorksApproachSection(mixed $raw): array
    {
        $defaults = [
            'title' => '',
            'emphasis' => '',
            'description' => '',
            'image_path' => null,
        ];

        if (! is_array($raw)) {
            return $defaults;
        }

        $out = $defaults;
        $out['title'] = isset($raw['title']) ? trim((string) $raw['title']) : '';
        $out['emphasis'] = isset($raw['emphasis']) ? trim((string) $raw['emphasis']) : '';
        $out['description'] = isset($raw['description']) ? trim((string) $raw['description']) : '';

        $path = isset($raw['image_path']) && is_string($raw['image_path']) && $raw['image_path'] !== ''
            ? trim($raw['image_path'])
            : null;
        if ($path !== null && (str_contains($path, '..') || ! str_starts_with($path, 'frontend/how-it-works-page/approach/'))) {
            $path = null;
        }
        $out['image_path'] = $path;

        return $out;
    }

    /**
     * @return array{title: string, emphasis: string, description: string, image_url: ?string}|null
     */
    public function howItWorksApproachSectionForView(): ?array
    {
        $m = self::normalizeHowItWorksApproachSection($this->how_it_works_approach_section);

        $hasContent = $m['title'] !== ''
            || $m['emphasis'] !== ''
            || $m['description'] !== ''
            || filled($m['image_path']);

        if (! $hasContent) {
            return null;
        }

        $displayTitle = $m['title'] !== '' ? $m['title'] : __('Our unique approach');
        $img = $m['image_path'];
        $imageUrl = filled($img) ? asset('storage/'.$img) : null;

        return [
            'title' => $displayTitle,
            'emphasis' => $m['emphasis'],
            'description' => $m['description'],
            'image_url' => $imageUrl,
        ];
    }

    /**
     * @return array{label: string, href: string}|null
     */
    public function homeHeroCtaOne(): ?array
    {
        return $this->homeHeroCtaPair(
            $this->home_hero_button1_label ?? null,
            $this->home_hero_button1_url ?? null,
        );
    }

    /**
     * @return array{label: string, href: string}|null
     */
    public function homeHeroCtaTwo(): ?array
    {
        return $this->homeHeroCtaPair(
            $this->home_hero_button2_label ?? null,
            $this->home_hero_button2_url ?? null,
        );
    }

    /**
     * @return array{label: string, href: string}|null
     */
    private function homeHeroCtaPair(?string $label, ?string $url): ?array
    {
        $l = $label !== null ? trim($label) : '';
        $u = $url !== null ? trim($url) : '';
        if ($l === '' || $u === '') {
            return null;
        }

        $href = self::normalizeHeroPublicUrl($u);
        if ($href === null) {
            return null;
        }

        return ['label' => $l, 'href' => $href];
    }

    public static function normalizeHeroPublicUrl(string $url): ?string
    {
        $url = trim($url);
        if ($url === '') {
            return null;
        }

        $lower = strtolower($url);
        if (str_starts_with($lower, 'javascript:') || str_starts_with($lower, 'data:')) {
            return null;
        }

        if (str_starts_with($url, '/') && ! str_starts_with($url, '//')) {
            return url($url);
        }

        if (filter_var($url, FILTER_VALIDATE_URL) !== false && preg_match('#^https?://#i', $url) === 1) {
            return $url;
        }

        return null;
    }

    /**
     * Normalized “How it works” home section (three steps + heading).
     *
     * @return array{heading: string, emphasis: string, steps: list<array{image_path: ?string, badge: string, title: string, body: string}>}
     */
    public static function normalizeHomeHowSection(mixed $raw): array
    {
        $defaults = [
            'heading' => '',
            'emphasis' => '',
            'steps' => [
                ['image_path' => null, 'badge' => '', 'title' => '', 'body' => ''],
                ['image_path' => null, 'badge' => '', 'title' => '', 'body' => ''],
                ['image_path' => null, 'badge' => '', 'title' => '', 'body' => ''],
            ],
        ];

        if (! is_array($raw)) {
            return $defaults;
        }

        $out = $defaults;
        $out['heading'] = isset($raw['heading']) ? trim((string) $raw['heading']) : '';
        $out['emphasis'] = isset($raw['emphasis']) ? trim((string) $raw['emphasis']) : '';

        $stepsIn = $raw['steps'] ?? [];
        if (! is_array($stepsIn)) {
            $stepsIn = [];
        }

        for ($i = 0; $i < 3; $i++) {
            $row = is_array($stepsIn[$i] ?? null) ? $stepsIn[$i] : [];
            $path = isset($row['image_path']) && is_string($row['image_path']) && $row['image_path'] !== ''
                ? trim($row['image_path'])
                : null;
            if ($path !== null && (str_contains($path, '..') || ! str_starts_with($path, 'frontend/how-it-works/'))) {
                $path = null;
            }
            $out['steps'][$i] = [
                'image_path' => $path,
                'badge' => isset($row['badge']) ? trim((string) $row['badge']) : '',
                'title' => isset($row['title']) ? trim((string) $row['title']) : '',
                'body' => isset($row['body']) ? trim((string) $row['body']) : '',
            ];
        }

        return $out;
    }

    /**
     * @return array{heading: string, emphasis: string, steps: list<array{badge: string, title: string, body: string, image_url: ?string}>}|null
     */
    public function homeHowSectionForView(): ?array
    {
        $merged = self::normalizeHomeHowSection($this->home_how_section);
        $heading = $merged['heading'];
        $emphasis = $merged['emphasis'];
        $steps = $merged['steps'];

        $hasContent = $heading !== ''
            || $emphasis !== ''
            || collect($steps)->contains(function (array $s): bool {
                return $s['badge'] !== ''
                    || $s['title'] !== ''
                    || $s['body'] !== ''
                    || filled($s['image_path']);
            });

        if (! $hasContent) {
            return null;
        }

        $displayHeading = $heading !== '' ? $heading : __('How it works');

        $outSteps = [];
        foreach ($steps as $s) {
            $p = $s['image_path'] ?? null;
            $outSteps[] = [
                'badge' => $s['badge'],
                'title' => $s['title'],
                'body' => $s['body'],
                'image_url' => filled($p) ? asset('storage/'.$p) : null,
            ];
        }

        return [
            'heading' => $displayHeading,
            'emphasis' => $emphasis,
            'steps' => $outSteps,
        ];
    }

    /**
     * Normalized “Why people love” home section (four feature columns + heading + CTA).
     *
     * @return array{heading: string, emphasis: string, description: string, features: list<array{image_path: ?string, link_label: string, link_url: string, text: string}>, cta_label: string, cta_url: string}
     */
    public static function normalizeHomeWhySection(mixed $raw): array
    {
        $emptyFeature = ['image_path' => null, 'link_label' => '', 'link_url' => '', 'text' => ''];
        $defaults = [
            'heading' => '',
            'emphasis' => '',
            'description' => '',
            'features' => [$emptyFeature, $emptyFeature, $emptyFeature, $emptyFeature],
            'cta_label' => '',
            'cta_url' => '',
        ];

        if (! is_array($raw)) {
            return $defaults;
        }

        $out = $defaults;
        $out['heading'] = isset($raw['heading']) ? trim((string) $raw['heading']) : '';
        $out['emphasis'] = isset($raw['emphasis']) ? trim((string) $raw['emphasis']) : '';
        $out['description'] = isset($raw['description']) ? trim((string) $raw['description']) : '';
        $out['cta_label'] = isset($raw['cta_label']) ? trim((string) $raw['cta_label']) : '';
        $out['cta_url'] = isset($raw['cta_url']) ? trim((string) $raw['cta_url']) : '';

        $featuresIn = $raw['features'] ?? [];
        if (! is_array($featuresIn)) {
            $featuresIn = [];
        }

        for ($i = 0; $i < 4; $i++) {
            $row = is_array($featuresIn[$i] ?? null) ? $featuresIn[$i] : [];
            $path = isset($row['image_path']) && is_string($row['image_path']) && $row['image_path'] !== ''
                ? trim($row['image_path'])
                : null;
            if ($path !== null && (str_contains($path, '..') || ! str_starts_with($path, 'frontend/home-why/'))) {
                $path = null;
            }
            $out['features'][$i] = [
                'image_path' => $path,
                'link_label' => isset($row['link_label']) ? trim((string) $row['link_label']) : '',
                'link_url' => isset($row['link_url']) ? trim((string) $row['link_url']) : '',
                'text' => isset($row['text']) ? trim((string) $row['text']) : '',
            ];
        }

        return $out;
    }

    /**
     * @return array{heading: string, emphasis: string, description: string, features: list<array{image_url: ?string, link_label: string, link_href: ?string, text: string}>, cta: ?array{label: string, href: string}}|null
     */
    public function homeWhySectionForView(): ?array
    {
        $merged = self::normalizeHomeWhySection($this->home_why_section);
        $heading = $merged['heading'];
        $emphasis = $merged['emphasis'];
        $description = $merged['description'];
        $features = $merged['features'];
        $ctaLabel = $merged['cta_label'];
        $ctaUrl = $merged['cta_url'];

        $hasFeatureContent = collect($features)->contains(function (array $f): bool {
            return filled($f['image_path'])
                || $f['link_label'] !== ''
                || $f['link_url'] !== ''
                || $f['text'] !== '';
        });

        $hasCta = $ctaLabel !== '' && $ctaUrl !== '';
        $hasContent = $heading !== ''
            || $emphasis !== ''
            || $description !== ''
            || $hasFeatureContent
            || $hasCta;

        if (! $hasContent) {
            return null;
        }

        $displayHeading = $heading !== '' ? $heading : __('Why people love SPOTMEE');

        $outFeatures = [];
        foreach ($features as $f) {
            $href = $f['link_url'] !== '' ? self::normalizeHeroPublicUrl($f['link_url']) : null;
            $p = $f['image_path'] ?? null;
            $outFeatures[] = [
                'image_url' => filled($p) ? asset('storage/'.$p) : null,
                'link_label' => $f['link_label'],
                'link_href' => $href,
                'text' => $f['text'],
            ];
        }

        $cta = null;
        if ($hasCta) {
            $href = self::normalizeHeroPublicUrl($ctaUrl);
            if ($href !== null) {
                $cta = ['label' => $ctaLabel, 'href' => $href];
            }
        }

        return [
            'heading' => $displayHeading,
            'emphasis' => $emphasis,
            'description' => $description,
            'features' => $outFeatures,
            'cta' => $cta,
        ];
    }

    /**
     * Normalized “Earn / host” split home section (title, three bullets, copy, CTA, footnote, image).
     *
     * @return array{heading: string, emphasis: string, points: list<string>, description: string, cta_label: string, cta_url: string, footnote: string, image_path: ?string}
     */
    public static function normalizeHomeEarnSection(mixed $raw): array
    {
        $defaults = [
            'heading' => '',
            'emphasis' => '',
            'points' => ['', '', ''],
            'description' => '',
            'cta_label' => '',
            'cta_url' => '',
            'footnote' => '',
            'image_path' => null,
        ];

        if (! is_array($raw)) {
            return $defaults;
        }

        $out = $defaults;
        $out['heading'] = isset($raw['heading']) ? trim((string) $raw['heading']) : '';
        $out['emphasis'] = isset($raw['emphasis']) ? trim((string) $raw['emphasis']) : '';
        $out['description'] = isset($raw['description']) ? trim((string) $raw['description']) : '';
        $out['cta_label'] = isset($raw['cta_label']) ? trim((string) $raw['cta_label']) : '';
        $out['cta_url'] = isset($raw['cta_url']) ? trim((string) $raw['cta_url']) : '';
        $out['footnote'] = isset($raw['footnote']) ? trim((string) $raw['footnote']) : '';

        $path = isset($raw['image_path']) && is_string($raw['image_path']) && $raw['image_path'] !== ''
            ? trim($raw['image_path'])
            : null;
        if ($path !== null && (str_contains($path, '..') || ! str_starts_with($path, 'frontend/home-earn/'))) {
            $path = null;
        }
        $out['image_path'] = $path;

        $pts = $raw['points'] ?? [];
        if (! is_array($pts)) {
            $pts = [];
        }
        for ($i = 0; $i < 3; $i++) {
            $out['points'][$i] = isset($pts[$i]) ? trim((string) $pts[$i]) : '';
        }

        return $out;
    }

    /**
     * @return array{heading: string, emphasis: string, points: list<string>, description: string, footnote: string, cta: ?array{label: string, href: string}, image_url: ?string}|null
     */
    public function homeEarnSectionForView(): ?array
    {
        $m = self::normalizeHomeEarnSection($this->home_earn_section);
        $hasPoint = collect($m['points'])->contains(fn (string $p): bool => $p !== '');
        $hasContent = $m['heading'] !== ''
            || $m['emphasis'] !== ''
            || $m['description'] !== ''
            || $m['footnote'] !== ''
            || ($m['cta_label'] !== '' && $m['cta_url'] !== '')
            || $hasPoint
            || filled($m['image_path']);

        if (! $hasContent) {
            return null;
        }

        $displayHeading = $m['heading'] !== '' ? $m['heading'] : __('Earn with your home gym');

        $cta = null;
        if ($m['cta_label'] !== '' && $m['cta_url'] !== '') {
            $href = self::normalizeHeroPublicUrl($m['cta_url']);
            if ($href !== null) {
                $cta = ['label' => $m['cta_label'], 'href' => $href];
            }
        }

        $img = $m['image_path'];

        return [
            'heading' => $displayHeading,
            'emphasis' => $m['emphasis'],
            'points' => $m['points'],
            'description' => $m['description'],
            'footnote' => $m['footnote'],
            'cta' => $cta,
            'image_url' => filled($img) ? asset('storage/'.$img) : null,
        ];
    }

    /**
     * Normalized “Community” home section (heading, subcopy, four image cards, CTA).
     *
     * @return array{heading: string, emphasis: string, description: string, cards: list<array{image_path: ?string, title: string, body: string}>, cta_label: string, cta_url: string}
     */
    public static function normalizeHomeCommunitySection(mixed $raw): array
    {
        $emptyCard = ['image_path' => null, 'title' => '', 'body' => ''];
        $defaults = [
            'heading' => '',
            'emphasis' => '',
            'description' => '',
            'cards' => [$emptyCard, $emptyCard, $emptyCard, $emptyCard],
            'cta_label' => '',
            'cta_url' => '',
        ];

        if (! is_array($raw)) {
            return $defaults;
        }

        $out = $defaults;
        $out['heading'] = isset($raw['heading']) ? trim((string) $raw['heading']) : '';
        $out['emphasis'] = isset($raw['emphasis']) ? trim((string) $raw['emphasis']) : '';
        $out['description'] = isset($raw['description']) ? trim((string) $raw['description']) : '';
        $out['cta_label'] = isset($raw['cta_label']) ? trim((string) $raw['cta_label']) : '';
        $out['cta_url'] = isset($raw['cta_url']) ? trim((string) $raw['cta_url']) : '';

        $cardsIn = $raw['cards'] ?? [];
        if (! is_array($cardsIn)) {
            $cardsIn = [];
        }

        for ($i = 0; $i < 4; $i++) {
            $row = is_array($cardsIn[$i] ?? null) ? $cardsIn[$i] : [];
            $path = isset($row['image_path']) && is_string($row['image_path']) && $row['image_path'] !== ''
                ? trim($row['image_path'])
                : null;
            if ($path !== null && (str_contains($path, '..') || ! str_starts_with($path, 'frontend/home-community/'))) {
                $path = null;
            }
            $out['cards'][$i] = [
                'image_path' => $path,
                'title' => isset($row['title']) ? trim((string) $row['title']) : '',
                'body' => isset($row['body']) ? trim((string) $row['body']) : '',
            ];
        }

        return $out;
    }

    /**
     * @return array{heading: string, emphasis: string, description: string, cards: list<array{image_url: ?string, title: string, body: string}>, cta: ?array{label: string, href: string}}|null
     */
    public function homeCommunitySectionForView(): ?array
    {
        $m = self::normalizeHomeCommunitySection($this->home_community_section);
        $hasCard = collect($m['cards'])->contains(function (array $c): bool {
            return filled($c['image_path']) || $c['title'] !== '' || $c['body'] !== '';
        });
        $hasCta = $m['cta_label'] !== '' && $m['cta_url'] !== '';
        $hasContent = $m['heading'] !== ''
            || $m['emphasis'] !== ''
            || $m['description'] !== ''
            || $hasCard
            || $hasCta;

        if (! $hasContent) {
            return null;
        }

        $displayHeading = $m['heading'] !== '' ? $m['heading'] : __('Community & discussions');

        $outCards = [];
        foreach ($m['cards'] as $c) {
            $p = $c['image_path'] ?? null;
            $outCards[] = [
                'image_url' => filled($p) ? asset('storage/'.$p) : null,
                'title' => $c['title'],
                'body' => $c['body'],
            ];
        }

        $cta = null;
        if ($hasCta) {
            $href = self::normalizeHeroPublicUrl($m['cta_url']);
            if ($href !== null) {
                $cta = ['label' => $m['cta_label'], 'href' => $href];
            }
        }

        return [
            'heading' => $displayHeading,
            'emphasis' => $m['emphasis'],
            'description' => $m['description'],
            'cards' => $outCards,
            'cta' => $cta,
        ];
    }

    /**
     * Normalized full-width promo banner (heading, optional highlight, CTA, background image).
     *
     * @return array{heading: string, emphasis: string, cta_label: string, cta_url: string, image_path: ?string}
     */
    public static function normalizeHomePromoBannerSection(mixed $raw): array
    {
        $defaults = [
            'heading' => '',
            'emphasis' => '',
            'cta_label' => '',
            'cta_url' => '',
            'image_path' => null,
        ];

        if (! is_array($raw)) {
            return $defaults;
        }

        $out = $defaults;
        $out['heading'] = isset($raw['heading']) ? trim((string) $raw['heading']) : '';
        $out['emphasis'] = isset($raw['emphasis']) ? trim((string) $raw['emphasis']) : '';
        $out['cta_label'] = isset($raw['cta_label']) ? trim((string) $raw['cta_label']) : '';
        $out['cta_url'] = isset($raw['cta_url']) ? trim((string) $raw['cta_url']) : '';

        $path = isset($raw['image_path']) && is_string($raw['image_path']) && $raw['image_path'] !== ''
            ? trim($raw['image_path'])
            : null;
        if ($path !== null && (str_contains($path, '..') || ! str_starts_with($path, 'frontend/home-promo-banner/'))) {
            $path = null;
        }
        $out['image_path'] = $path;

        return $out;
    }

    /**
     * @return array{heading: string, emphasis: string, cta: ?array{label: string, href: string}, image_url: ?string, on_image: bool}|null
     */
    public function homePromoBannerSectionForView(): ?array
    {
        $m = self::normalizeHomePromoBannerSection($this->home_promo_banner_section);
        $hasCtaPair = $m['cta_label'] !== '' && $m['cta_url'] !== '';
        $hasContent = $m['heading'] !== ''
            || $m['emphasis'] !== ''
            || $hasCtaPair
            || filled($m['image_path']);

        if (! $hasContent) {
            return null;
        }

        $displayHeading = $m['heading'] !== '' ? $m['heading'] : __('Your perfect workout space');

        $cta = null;
        if ($hasCtaPair) {
            $href = self::normalizeHeroPublicUrl($m['cta_url']);
            if ($href !== null) {
                $cta = ['label' => $m['cta_label'], 'href' => $href];
            }
        }

        $img = $m['image_path'];
        $imageUrl = filled($img) ? asset('storage/'.$img) : null;

        return [
            'heading' => $displayHeading,
            'emphasis' => $m['emphasis'],
            'cta' => $cta,
            'image_url' => $imageUrl,
            'on_image' => filled($imageUrl),
        ];
    }

    public function hasStripeTestSecret(): bool
    {
        return filled($this->stripe_test_secret_key);
    }

    public function hasStripeLiveSecret(): bool
    {
        return filled($this->stripe_live_secret_key);
    }

    /**
     * Safe hint for admin UI: confirms a secret is stored without exposing the full key in HTML.
     */
    public function maskedStripeTestSecretKey(): ?string
    {
        return self::maskStripeSecretForDisplay($this->stripe_test_secret_key);
    }

    /**
     * @see maskedStripeTestSecretKey()
     */
    public function maskedStripeLiveSecretKey(): ?string
    {
        return self::maskStripeSecretForDisplay($this->stripe_live_secret_key);
    }

    private static function maskStripeSecretForDisplay(mixed $key): ?string
    {
        $key = is_string($key) ? trim($key) : '';
        if ($key === '') {
            return null;
        }

        $len = strlen($key);
        if ($len <= 12) {
            return '********';
        }

        $last = substr($key, -4);
        foreach (['sk_test_', 'rk_test_', 'sk_live_', 'rk_live_'] as $prefix) {
            if (str_starts_with($key, $prefix)) {
                return $prefix.'••••'.$last;
            }
        }

        return '••••••••'.$last;
    }

    public function stripePublishableKey(): ?string
    {
        $mode = strtolower((string) ($this->stripe_mode ?? 'test'));

        return $mode === 'live'
            ? (filled($this->stripe_live_publishable_key) ? (string) $this->stripe_live_publishable_key : null)
            : (filled($this->stripe_test_publishable_key) ? (string) $this->stripe_test_publishable_key : null);
    }

    public function stripeSecretKey(): ?string
    {
        $mode = strtolower((string) ($this->stripe_mode ?? 'test'));

        return $mode === 'live'
            ? ($this->hasStripeLiveSecret() ? $this->stripe_live_secret_key : null)
            : ($this->hasStripeTestSecret() ? $this->stripe_test_secret_key : null);
    }

    public function isStripeConfiguredForPayments(): bool
    {
        return filled($this->stripePublishableKey()) && filled($this->stripeSecretKey());
    }

    public function hasWebhookBookingCompletedSecret(): bool
    {
        return filled($this->webhook_booking_completed_secret);
    }

    public function hasWebhookBookingCancelledSecret(): bool
    {
        return filled($this->webhook_booking_cancelled_secret);
    }

    /**
     * Normalized notification email overrides (all configurable template keys).
     *
     * @return array<string, array{subject: string, body_html: string}>
     */
    public function notificationEmailTemplatesNormalized(): array
    {
        $raw = $this->notification_email_templates;
        if (! is_array($raw)) {
            $raw = [];
        }
        $out = [];
        foreach (SiteEmailTemplateService::allTemplateKeys() as $key) {
            $slot = $raw[$key] ?? [];
            $out[$key] = [
                'subject' => isset($slot['subject']) && is_string($slot['subject']) ? $slot['subject'] : '',
                'body_html' => isset($slot['body_html']) && is_string($slot['body_html']) ? $slot['body_html'] : '',
            ];
        }

        return $out;
    }

    /**
     * “Become a Host” link when no custom URL is configured.
     */
    public function resolvedHostRegistrationUrl(): string
    {
        if (filled($this->legal_host_registration_url)) {
            return $this->legal_host_registration_url;
        }

        return route('host.apply');
    }

    /**
     * Redirect target after a guest cancels via email link (when implemented).
     */
    public function resolvedBookingCancelResultUrl(): string
    {
        if (filled($this->booking_cancel_result_url)) {
            return $this->booking_cancel_result_url;
        }

        return url('/');
    }

    /**
     * Total customer price = base price + commission portion (percentage of base).
     */
    public static function tierTotalWithCommission(?float $basePrice, ?float $commissionPct): ?float
    {
        if ($basePrice === null || $commissionPct === null) {
            return null;
        }

        return round($basePrice * (1 + ($commissionPct / 100)), 2);
    }

    /**
     * Guest-facing session rates for a host tier (base + admin commission %), matching gym listing settings.
     *
     * @return array{tier: string, rate_40min: ?float, rate_1hr: ?float}
     */
    public function publicGuestTierRates(string $tier = 'silver'): array
    {
        $tier = match (strtolower($tier)) {
            'gold' => 'gold',
            'platinum' => 'platinum',
            default => 'silver',
        };

        $base40 = $this->{"{$tier}_tier_price_40_min"};
        $pct40 = $this->{"{$tier}_tier_admin_commission_40_min_pct"};
        $base1 = $this->{"{$tier}_tier_price_1_hour"};
        $pct1 = $this->{"{$tier}_tier_admin_commission_1_hour_pct"};

        return [
            'tier' => $tier,
            'rate_40min' => is_numeric($base40) && is_numeric($pct40)
                ? self::tierTotalWithCommission((float) $base40, (float) $pct40)
                : null,
            'rate_1hr' => is_numeric($base1) && is_numeric($pct1)
                ? self::tierTotalWithCommission((float) $base1, (float) $pct1)
                : null,
        ];
    }

    /**
     * Guest-facing personal training add-on price per slot (base + admin commission %).
     */
    public function publicPtSlotCustomerPrice(string $tier = 'silver'): float
    {
        $tier = match (strtolower($tier)) {
            'gold' => 'gold',
            'platinum' => 'platinum',
            default => 'silver',
        };

        $base = $this->{"pt_{$tier}_price_per_slot"};
        $pct = $this->{"pt_{$tier}_admin_commission_pct"};
        if (! is_numeric($base)) {
            return 0.0;
        }

        $total = self::tierTotalWithCommission((float) $base, is_numeric($pct) ? (float) $pct : 0.0);

        return $total !== null ? round((float) $total, 2) : 0.0;
    }

    /** @deprecated Use {@see tierTotalWithCommission()} */
    public static function silverTierTotal(?float $basePrice, ?float $commissionPct): ?float
    {
        return self::tierTotalWithCommission($basePrice, $commissionPct);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'stripe_test_secret_key' => 'encrypted',
            'stripe_live_secret_key' => 'encrypted',
            'webhook_booking_completed_secret' => 'encrypted',
            'webhook_booking_cancelled_secret' => 'encrypted',
            'silver_tier_price_1_hour' => 'decimal:2',
            'silver_tier_price_40_min' => 'decimal:2',
            'silver_tier_admin_commission_1_hour_pct' => 'decimal:2',
            'silver_tier_admin_commission_40_min_pct' => 'decimal:2',
            'gold_tier_price_1_hour' => 'decimal:2',
            'gold_tier_price_40_min' => 'decimal:2',
            'gold_tier_admin_commission_1_hour_pct' => 'decimal:2',
            'gold_tier_admin_commission_40_min_pct' => 'decimal:2',
            'platinum_tier_price_1_hour' => 'decimal:2',
            'platinum_tier_price_40_min' => 'decimal:2',
            'platinum_tier_admin_commission_1_hour_pct' => 'decimal:2',
            'platinum_tier_admin_commission_40_min_pct' => 'decimal:2',
            'pt_silver_price_per_slot' => 'decimal:2',
            'pt_silver_admin_commission_pct' => 'decimal:2',
            'pt_gold_price_per_slot' => 'decimal:2',
            'pt_gold_admin_commission_pct' => 'decimal:2',
            'pt_platinum_price_per_slot' => 'decimal:2',
            'pt_platinum_admin_commission_pct' => 'decimal:2',
            'home_how_section' => 'array',
            'home_why_section' => 'array',
            'home_earn_section' => 'array',
            'home_community_section' => 'array',
            'home_promo_banner_section' => 'array',
            'how_it_works_intro_section' => 'array',
            'how_it_works_approach_section' => 'array',
            'faq_page_items' => 'array',
            'footer_social_urls' => 'array',
            'notification_email_templates' => 'array',
            'smtp_enabled' => 'boolean',
            'smtp_port' => 'integer',
            'smtp_password' => 'encrypted',
            'host_registration_auto_approve' => 'boolean',
        ];
    }
}
