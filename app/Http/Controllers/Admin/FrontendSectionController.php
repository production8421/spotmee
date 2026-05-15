<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateFrontendHomeHeroRequest;
use App\Http\Requests\Admin\UpdateFooterRequest;
use App\Http\Requests\Admin\UpdateFrontendPageHeroRequest;
use App\Http\Requests\Admin\UpdateHowItWorksPageRequest;
use App\Models\ApplicationSetting;
use App\Models\MediaAsset;
use App\Services\FrontendHeroMediaImporter;
use App\Services\HomeCommunitySectionImageImporter;
use App\Services\HomePromoBannerSectionImageImporter;
use App\Services\HomeEarnSectionImageImporter;
use App\Services\HomeWhySectionImageImporter;
use App\Services\HowItWorksPageApproachImageImporter;
use App\Services\HowItWorksPageIntroImageImporter;
use App\Services\HowItWorksStepImageImporter;
use App\Support\LegalDocument;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FrontendSectionController extends Controller
{
    public function home(): View
    {
        $settings = ApplicationSetting::instance();

        return view('admin.frontend.home.edit', [
            'pageTitle' => __('Frontend').' — '.__('Home'),
            'breadcrumbActive' => __('Home'),
            'settings' => $settings,
            'how' => ApplicationSetting::normalizeHomeHowSection($settings->home_how_section),
            'why' => ApplicationSetting::normalizeHomeWhySection($settings->home_why_section),
            'earn' => ApplicationSetting::normalizeHomeEarnSection($settings->home_earn_section),
            'community' => ApplicationSetting::normalizeHomeCommunitySection($settings->home_community_section),
            'promoBanner' => ApplicationSetting::normalizeHomePromoBannerSection($settings->home_promo_banner_section),
        ]);
    }

    public function updateHome(UpdateFrontendHomeHeroRequest $request): RedirectResponse
    {
        $settings = ApplicationSetting::instance();
        $heading = $request->input('home_hero_heading');
        $settings->home_hero_heading = $heading !== null && trim((string) $heading) !== ''
            ? trim((string) $heading)
            : null;

        $settings->home_hero_button1_label = $this->nullableTrimmedString($request->input('home_hero_button1_label'));
        $settings->home_hero_button1_url = $this->nullableTrimmedString($request->input('home_hero_button1_url'));
        $settings->home_hero_button2_label = $this->nullableTrimmedString($request->input('home_hero_button2_label'));
        $settings->home_hero_button2_url = $this->nullableTrimmedString($request->input('home_hero_button2_url'));

        $type = (string) $request->input('home_hero_background_type');

        if ($type === 'color') {
            if (filled($settings->home_hero_background_path)) {
                Storage::disk('public')->delete($settings->home_hero_background_path);
            }
            $settings->home_hero_background_path = null;
            $settings->home_hero_background_type = 'color';
            $settings->home_hero_background_color = (string) $request->input('home_hero_background_color');
        } else {
            $settings->home_hero_background_color = null;
            if ($request->filled('home_hero_background_media_id')) {
                $asset = MediaAsset::query()->findOrFail($request->integer('home_hero_background_media_id'));
                $newPath = FrontendHeroMediaImporter::copyToPublic($asset, $type);
                if ($newPath !== null) {
                    if (filled($settings->home_hero_background_path)) {
                        Storage::disk('public')->delete($settings->home_hero_background_path);
                    }
                    $settings->home_hero_background_path = $newPath;
                    $settings->home_hero_background_type = $type;
                }
            } else {
                $settings->home_hero_background_type = $type;
            }
        }

        $this->syncHomeHowSectionFromRequest($request, $settings);
        $this->syncHomeWhySectionFromRequest($request, $settings);
        $this->syncHomeEarnSectionFromRequest($request, $settings);
        $this->syncHomeCommunitySectionFromRequest($request, $settings);
        $this->syncHomePromoBannerSectionFromRequest($request, $settings);

        $settings->save();

        return redirect()
            ->route('admin.frontend.home')
            ->with('status', __('Home page saved.'));
    }

    public function howItWorks(): View
    {
        $settings = ApplicationSetting::instance();

        return view('admin.frontend.how-it-works.edit', [
            'pageTitle' => __('Frontend').' — '.__('How It Works'),
            'breadcrumbActive' => __('How It Works'),
            'settings' => $settings,
            'intro' => ApplicationSetting::normalizeHowItWorksIntroSection($settings->how_it_works_intro_section),
            'approach' => ApplicationSetting::normalizeHowItWorksApproachSection($settings->how_it_works_approach_section),
        ]);
    }

    public function updateHowItWorks(UpdateHowItWorksPageRequest $request): RedirectResponse
    {
        $settings = ApplicationSetting::instance();

        $settings->how_it_works_hero_title = $this->nullableTrimmedString($request->input('how_it_works_hero_title'));

        $color = trim((string) $request->input('how_it_works_hero_background_color', ''));
        $settings->how_it_works_hero_background_color = preg_match('/^#[0-9A-Fa-f]{6}$/', $color) === 1 ? $color : null;

        $this->syncHowItWorksIntroSectionFromRequest($request, $settings);
        $this->syncHowItWorksApproachSectionFromRequest($request, $settings);

        $settings->save();

        return redirect()
            ->route('admin.frontend.how-it-works')
            ->with('status', __('How It Works page saved.'));
    }

    public function findAGym(): View
    {
        return $this->frontendPageHeroEdit(
            'find_a_gym',
            __('Find a Gym'),
            __('Page title bar for the public Find a Gym page (centered title on a solid color band).'),
            'admin.frontend.find-a-gym.update',
        );
    }

    public function becomeAHost(): View
    {
        return $this->frontendPageHeroEdit(
            'become_a_host',
            __('Become a Host'),
            __('Page title bar for the public Become a Host page (centered title on a solid color band).'),
            'admin.frontend.become-a-host.update',
        );
    }

    public function faq(): View
    {
        return $this->frontendPageHeroEdit(
            'faq',
            __('FAQ'),
            __('Page title bar for the public FAQ page (centered title on a solid color band).'),
            'admin.frontend.faq.update',
        );
    }

    public function contact(): View
    {
        return $this->frontendPageHeroEdit(
            'contact',
            __('Contact'),
            __('Page title bar for the public Contact page (centered title on a solid color band).'),
            'admin.frontend.contact.update',
        );
    }

    public function updateFrontendPageHero(UpdateFrontendPageHeroRequest $request): RedirectResponse
    {
        $settings = ApplicationSetting::instance();
        $prefix = $request->heroPrefix();

        $settings->{$prefix.'_hero_title'} = $this->nullableTrimmedString($request->input("{$prefix}_hero_title"));

        $color = trim((string) $request->input("{$prefix}_hero_background_color", ''));
        $settings->{$prefix.'_hero_background_color'} = preg_match('/^#[0-9A-Fa-f]{6}$/', $color) === 1 ? $color : null;

        if ($prefix === 'faq') {
            $settings->faq_page_items = ApplicationSetting::normalizeFaqPageItemsFromRequestInput($request->input('faq_items', []));
        }

        if ($prefix === 'waiver_liability_host') {
            $this->syncWaiverPdfs($request, $settings, 'host');
        }

        if ($prefix === 'waiver_liability_user') {
            $this->syncWaiverPdfs($request, $settings, 'user');
        }

        $settings->save();

        return redirect()
            ->route($request->heroRedirectRouteName())
            ->with('status', $request->heroSavedStatusMessage());
    }

    public function waiverOfLiabilityHost(): View
    {
        return $this->frontendPageHeroEdit(
            'waiver_liability_host',
            __('Waiver of Liability Host'),
            __('Page title bar for the public Waiver of Liability (Host) page (centered title on a solid color band).'),
            'admin.frontend.waiver-of-liability-host.update',
        );
    }

    public function waiverOfLiabilityUser(): View
    {
        return $this->frontendPageHeroEdit(
            'waiver_liability_user',
            __('Waiver of Liability User'),
            __('Page title bar for the public Waiver of Liability (User) page (centered title on a solid color band).'),
            'admin.frontend.waiver-of-liability-user.update',
        );
    }

    public function cancellationPolicy(): View
    {
        return $this->frontendPageHeroEdit(
            'cancellation_policy',
            __('Cancellation Policy'),
            __('Page title bar for the public Cancellation Policy page (centered title on a solid color band).'),
            'admin.frontend.cancellation-policy.update',
        );
    }

    public function footer(): View
    {
        $settings = ApplicationSetting::instance();

        return view('admin.frontend.footer.edit', [
            'pageTitle' => __('Frontend').' — '.__('Footer'),
            'settings' => $settings,
        ]);
    }

    public function updateFooter(UpdateFooterRequest $request): RedirectResponse
    {
        $settings = ApplicationSetting::instance();
        $input = $request->input('footer_social_urls', []);
        $input = is_array($input) ? $input : [];
        $settings->footer_social_urls = ApplicationSetting::normalizeFooterSocialUrlsForStorage($input);
        $settings->save();

        return redirect()
            ->route('admin.frontend.footer')
            ->with('status', __('Footer saved.'));
    }

    /**
     * @param  'find_a_gym'|'become_a_host'|'faq'|'contact'|'waiver_liability_host'|'waiver_liability_user'|'cancellation_policy'  $prefix
     */
    private function frontendPageHeroEdit(string $prefix, string $sectionHeading, string $heroHelp, string $updateRouteName): View
    {
        $settings = ApplicationSetting::instance();

        $data = [
            'pageTitle' => __('Frontend').' — '.$sectionHeading,
            'sectionHeading' => $sectionHeading,
            'breadcrumbActive' => $sectionHeading,
            'settings' => $settings,
            'prefix' => $prefix,
            'heroHelp' => $heroHelp,
            'defaultHeroTitle' => $sectionHeading,
            'updateUrl' => route($updateRouteName),
        ];

        if ($prefix === 'faq') {
            $data['faqItemRows'] = ApplicationSetting::normalizeFaqPageItemsForForm(
                old('faq_items', $settings->faq_page_items)
            );
        }

        if (in_array($prefix, ['waiver_liability_host', 'waiver_liability_user'], true)) {
            $audience = $prefix === 'waiver_liability_user' ? 'user' : 'host';
            $data['waiverPdfSections'] = $this->buildWaiverPdfSections($audience, $settings);
            $data['waiverPdfUploadHeading'] = $audience === 'user'
                ? __('User waiver PDF documents')
                : __('Host waiver PDF documents');
            $data['waiverPdfUploadHelp'] = $audience === 'user'
                ? __('Upload PDFs shown on the public Waiver of Liability (User) page (sections 6 and 7). Max 20 MB each.')
                : __('Upload PDFs shown on the public Waiver of Liability (Host) page (sections 6 and 7). Max 20 MB each.');
        }

        return view('admin.frontend.page-hero.edit', $data);
    }

    private function nullableTrimmedString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $s = trim((string) $value);

        return $s === '' ? null : $s;
    }

    private function syncHowItWorksIntroSectionFromRequest(Request $request, ApplicationSetting $settings): void
    {
        $prev = ApplicationSetting::normalizeHowItWorksIntroSection($settings->how_it_works_intro_section);
        $imagePath = $prev['image_path'] ?? null;

        if ($request->boolean('intro_remove_image')) {
            if ($imagePath !== null) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = null;
        } elseif ($request->filled('intro_media_id')) {
            $asset = MediaAsset::query()->findOrFail($request->integer('intro_media_id'));
            $newPath = HowItWorksPageIntroImageImporter::copyToPublic($asset);
            if ($newPath !== null) {
                if ($imagePath !== null) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $newPath;
            }
        }

        $heading = trim((string) $request->input('intro_heading', ''));
        $emphasis = trim((string) $request->input('intro_heading_emphasis', ''));
        $subtitle = trim((string) $request->input('intro_subtitle', ''));
        $description1 = trim((string) $request->input('intro_description_1', ''));
        $description2 = trim((string) $request->input('intro_description_2', ''));
        $button1Label = trim((string) $request->input('intro_button1_label', ''));
        $button1Url = trim((string) $request->input('intro_button1_url', ''));
        $button2Label = trim((string) $request->input('intro_button2_label', ''));
        $button2Url = trim((string) $request->input('intro_button2_url', ''));

        $allEmpty = $heading === ''
            && $emphasis === ''
            && $subtitle === ''
            && $description1 === ''
            && $description2 === ''
            && $button1Label === ''
            && $button1Url === ''
            && $button2Label === ''
            && $button2Url === ''
            && $imagePath === null;

        $settings->how_it_works_intro_section = $allEmpty ? null : [
            'heading' => $heading,
            'emphasis' => $emphasis,
            'subtitle' => $subtitle,
            'description_1' => $description1,
            'description_2' => $description2,
            'button1_label' => $button1Label,
            'button1_url' => $button1Url,
            'button2_label' => $button2Label,
            'button2_url' => $button2Url,
            'image_path' => $imagePath,
        ];
    }

    private function syncHowItWorksApproachSectionFromRequest(Request $request, ApplicationSetting $settings): void
    {
        $prev = ApplicationSetting::normalizeHowItWorksApproachSection($settings->how_it_works_approach_section);
        $imagePath = $prev['image_path'] ?? null;

        if ($request->boolean('approach_remove_image')) {
            if ($imagePath !== null) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = null;
        } elseif ($request->filled('approach_media_id')) {
            $asset = MediaAsset::query()->findOrFail($request->integer('approach_media_id'));
            $newPath = HowItWorksPageApproachImageImporter::copyToPublic($asset);
            if ($newPath !== null) {
                if ($imagePath !== null) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $newPath;
            }
        }

        $title = trim((string) $request->input('approach_title', ''));
        $emphasis = trim((string) $request->input('approach_title_emphasis', ''));
        $description = trim((string) $request->input('approach_description', ''));

        $allEmpty = $title === '' && $emphasis === '' && $description === '' && $imagePath === null;

        $settings->how_it_works_approach_section = $allEmpty ? null : [
            'title' => $title,
            'emphasis' => $emphasis,
            'description' => $description,
            'image_path' => $imagePath,
        ];
    }

    private function syncHomeHowSectionFromRequest(Request $request, ApplicationSetting $settings): void
    {
        $prev = ApplicationSetting::normalizeHomeHowSection($settings->home_how_section);

        $heading = trim((string) $request->input('how_heading', ''));
        $emphasis = trim((string) $request->input('how_heading_emphasis', ''));

        $steps = [];
        for ($i = 0; $i < 3; $i++) {
            $n = $i + 1;
            $path = $prev['steps'][$i]['image_path'] ?? null;
            if ($request->filled("how_step_{$n}_media_id")) {
                $asset = MediaAsset::query()->findOrFail($request->integer("how_step_{$n}_media_id"));
                $newPath = HowItWorksStepImageImporter::copyToPublic($asset);
                if ($newPath !== null) {
                    if ($path !== null) {
                        Storage::disk('public')->delete($path);
                    }
                    $path = $newPath;
                }
            }
            $steps[] = [
                'image_path' => $path,
                'badge' => trim((string) $request->input("how_step_{$n}_badge", '')),
                'title' => trim((string) $request->input("how_step_{$n}_title", '')),
                'body' => trim((string) $request->input("how_step_{$n}_body", '')),
            ];
        }

        $allEmpty = $heading === '' && $emphasis === '' && collect($steps)->every(function (array $s): bool {
            return $s['badge'] === '' && $s['title'] === '' && $s['body'] === '' && $s['image_path'] === null;
        });

        $settings->home_how_section = $allEmpty ? null : [
            'heading' => $heading,
            'emphasis' => $emphasis,
            'steps' => $steps,
        ];
    }

    private function syncHomeWhySectionFromRequest(Request $request, ApplicationSetting $settings): void
    {
        $prev = ApplicationSetting::normalizeHomeWhySection($settings->home_why_section);

        $heading = trim((string) $request->input('why_heading', ''));
        $emphasis = trim((string) $request->input('why_heading_emphasis', ''));
        $description = trim((string) $request->input('why_description', ''));
        $ctaLabel = trim((string) $request->input('why_cta_label', ''));
        $ctaUrl = trim((string) $request->input('why_cta_url', ''));

        $features = [];
        for ($i = 0; $i < 4; $i++) {
            $n = $i + 1;
            $path = $prev['features'][$i]['image_path'] ?? null;
            if ($request->filled("why_feature_{$n}_media_id")) {
                $asset = MediaAsset::query()->findOrFail($request->integer("why_feature_{$n}_media_id"));
                $newPath = HomeWhySectionImageImporter::copyToPublic($asset);
                if ($newPath !== null) {
                    if ($path !== null) {
                        Storage::disk('public')->delete($path);
                    }
                    $path = $newPath;
                }
            }
            $features[] = [
                'image_path' => $path,
                'link_label' => trim((string) $request->input("why_feature_{$n}_link_label", '')),
                'link_url' => trim((string) $request->input("why_feature_{$n}_link_url", '')),
                'text' => trim((string) $request->input("why_feature_{$n}_text", '')),
            ];
        }

        $allEmpty = $heading === ''
            && $emphasis === ''
            && $description === ''
            && $ctaLabel === ''
            && $ctaUrl === ''
            && collect($features)->every(function (array $f): bool {
                return $f['link_label'] === ''
                    && $f['link_url'] === ''
                    && $f['text'] === ''
                    && $f['image_path'] === null;
            });

        $settings->home_why_section = $allEmpty ? null : [
            'heading' => $heading,
            'emphasis' => $emphasis,
            'description' => $description,
            'features' => $features,
            'cta_label' => $ctaLabel,
            'cta_url' => $ctaUrl,
        ];
    }

    private function syncHomeEarnSectionFromRequest(Request $request, ApplicationSetting $settings): void
    {
        $prev = ApplicationSetting::normalizeHomeEarnSection($settings->home_earn_section);
        $imagePath = $prev['image_path'] ?? null;

        if ($request->filled('earn_media_id')) {
            $asset = MediaAsset::query()->findOrFail($request->integer('earn_media_id'));
            $newPath = HomeEarnSectionImageImporter::copyToPublic($asset);
            if ($newPath !== null) {
                if ($imagePath !== null) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $newPath;
            }
        }

        $heading = trim((string) $request->input('earn_heading', ''));
        $emphasis = trim((string) $request->input('earn_heading_emphasis', ''));
        $description = trim((string) $request->input('earn_description', ''));
        $ctaLabel = trim((string) $request->input('earn_cta_label', ''));
        $ctaUrl = trim((string) $request->input('earn_cta_url', ''));
        $footnote = trim((string) $request->input('earn_footnote', ''));

        $points = [];
        for ($i = 1; $i <= 3; $i++) {
            $points[] = trim((string) $request->input("earn_point_{$i}", ''));
        }

        $allEmpty = $heading === ''
            && $emphasis === ''
            && $description === ''
            && $footnote === ''
            && $ctaLabel === ''
            && $ctaUrl === ''
            && $imagePath === null
            && collect($points)->every(fn (string $p): bool => $p === '');

        $settings->home_earn_section = $allEmpty ? null : [
            'heading' => $heading,
            'emphasis' => $emphasis,
            'points' => $points,
            'description' => $description,
            'cta_label' => $ctaLabel,
            'cta_url' => $ctaUrl,
            'footnote' => $footnote,
            'image_path' => $imagePath,
        ];
    }

    private function syncHomeCommunitySectionFromRequest(Request $request, ApplicationSetting $settings): void
    {
        $prev = ApplicationSetting::normalizeHomeCommunitySection($settings->home_community_section);

        $heading = trim((string) $request->input('community_heading', ''));
        $emphasis = trim((string) $request->input('community_heading_emphasis', ''));
        $description = trim((string) $request->input('community_description', ''));
        $ctaLabel = trim((string) $request->input('community_cta_label', ''));
        $ctaUrl = trim((string) $request->input('community_cta_url', ''));

        $cards = [];
        for ($i = 0; $i < 4; $i++) {
            $n = $i + 1;
            $path = $prev['cards'][$i]['image_path'] ?? null;
            if ($request->filled("community_card_{$n}_media_id")) {
                $asset = MediaAsset::query()->findOrFail($request->integer("community_card_{$n}_media_id"));
                $newPath = HomeCommunitySectionImageImporter::copyToPublic($asset);
                if ($newPath !== null) {
                    if ($path !== null) {
                        Storage::disk('public')->delete($path);
                    }
                    $path = $newPath;
                }
            }
            $cards[] = [
                'image_path' => $path,
                'title' => trim((string) $request->input("community_card_{$n}_title", '')),
                'body' => trim((string) $request->input("community_card_{$n}_body", '')),
            ];
        }

        $allEmpty = $heading === ''
            && $emphasis === ''
            && $description === ''
            && $ctaLabel === ''
            && $ctaUrl === ''
            && collect($cards)->every(function (array $c): bool {
                return $c['title'] === '' && $c['body'] === '' && $c['image_path'] === null;
            });

        $settings->home_community_section = $allEmpty ? null : [
            'heading' => $heading,
            'emphasis' => $emphasis,
            'description' => $description,
            'cards' => $cards,
            'cta_label' => $ctaLabel,
            'cta_url' => $ctaUrl,
        ];
    }

    private function syncHomePromoBannerSectionFromRequest(Request $request, ApplicationSetting $settings): void
    {
        $prev = ApplicationSetting::normalizeHomePromoBannerSection($settings->home_promo_banner_section);
        $imagePath = $prev['image_path'] ?? null;

        if ($request->filled('promo_banner_media_id')) {
            $asset = MediaAsset::query()->findOrFail($request->integer('promo_banner_media_id'));
            $newPath = HomePromoBannerSectionImageImporter::copyToPublic($asset);
            if ($newPath !== null) {
                if ($imagePath !== null) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $newPath;
            }
        }

        $heading = trim((string) $request->input('promo_banner_heading', ''));
        $emphasis = trim((string) $request->input('promo_banner_heading_emphasis', ''));
        $ctaLabel = trim((string) $request->input('promo_banner_cta_label', ''));
        $ctaUrl = trim((string) $request->input('promo_banner_cta_url', ''));

        $allEmpty = $heading === ''
            && $emphasis === ''
            && $ctaLabel === ''
            && $ctaUrl === ''
            && $imagePath === null;

        $settings->home_promo_banner_section = $allEmpty ? null : [
            'heading' => $heading,
            'emphasis' => $emphasis,
            'cta_label' => $ctaLabel,
            'cta_url' => $ctaUrl,
            'image_path' => $imagePath,
        ];
    }

    /**
     * @return list<array{key: string, label: string, url: ?string, input_name: string, remove_name: string}>
     */
    private function buildWaiverPdfSections(string $audience, ApplicationSetting $settings): array
    {
        $pdfs = config("legal.{$audience}_waiver_pdfs", []);
        $pathColumns = config("legal.{$audience}_waiver_path_columns", []);
        $uploadFields = config("legal.{$audience}_waiver_upload_fields", []);

        if (! is_array($pdfs)) {
            return [];
        }

        return collect($pdfs)
            ->map(function (array $meta, string $key) use ($settings, $pathColumns, $uploadFields, $audience): array {
                $pathColumn = $pathColumns[$key] ?? null;
                $fields = $uploadFields[$key] ?? [];
                $resolved = LegalDocument::waiverPdfResolved($audience, $key);

                return [
                    'key' => $key,
                    'label' => (string) ($meta['label'] ?? $key),
                    'url' => $resolved['url'] ?? null,
                    'input_name' => (string) ($fields['file'] ?? "legal_{$audience}_{$key}_pdf"),
                    'remove_name' => (string) ($fields['remove'] ?? "remove_legal_{$audience}_{$key}_pdf"),
                ];
            })
            ->values()
            ->all();
    }

    private function syncWaiverPdfs(UpdateFrontendPageHeroRequest $request, ApplicationSetting $settings, string $audience): void
    {
        $disk = Storage::disk('public');
        $uploadFields = config("legal.{$audience}_waiver_upload_fields", []);
        $pathColumns = config("legal.{$audience}_waiver_path_columns", []);

        if (! is_array($uploadFields)) {
            return;
        }

        foreach ($uploadFields as $key => $fields) {
            $pathColumn = $pathColumns[$key] ?? null;
            if ($pathColumn === null || ! is_array($fields)) {
                continue;
            }

            $currentPath = $settings->{$pathColumn};

            if ($request->boolean($fields['remove'] ?? '') && filled($currentPath)) {
                $disk->delete($currentPath);
                $settings->{$pathColumn} = null;
                $currentPath = null;
            }

            $fileInput = $fields['file'] ?? '';
            if ($fileInput !== '' && $request->hasFile($fileInput)) {
                $target = LegalDocument::waiverStoragePath($audience, $key);
                if (filled($currentPath) && $currentPath !== $target) {
                    $disk->delete($currentPath);
                }
                $request->file($fileInput)->storeAs(
                    dirname($target),
                    basename($target),
                    'public'
                );
                $settings->{$pathColumn} = $target;
            }
        }
    }
}
