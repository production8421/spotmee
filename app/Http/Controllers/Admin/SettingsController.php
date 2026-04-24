<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateApplicationSettingsRequest;
use App\Models\ApplicationSetting;
use App\Models\MediaAsset;
use App\Services\BrandingFromMediaImporter;
use App\Services\Mail\SiteEmailTemplateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => ApplicationSetting::instance(),
        ]);
    }

    public function update(UpdateApplicationSettingsRequest $request): RedirectResponse
    {
        $settings = ApplicationSetting::instance();

        if ($request->boolean('remove_header_logo')) {
            if ($settings->header_logo_path) {
                Storage::disk('public')->delete($settings->header_logo_path);
            }
            $settings->header_logo_path = null;
        }

        if ($request->boolean('remove_footer_logo')) {
            if ($settings->footer_logo_path) {
                Storage::disk('public')->delete($settings->footer_logo_path);
            }
            $settings->footer_logo_path = null;
        }

        if (! $request->boolean('remove_header_logo') && $request->filled('header_logo_media_id')) {
            $asset = MediaAsset::query()->find($request->integer('header_logo_media_id'));
            if ($asset) {
                $newPath = BrandingFromMediaImporter::copyToPublicBranding($asset);
                if ($newPath !== null) {
                    if ($settings->header_logo_path) {
                        Storage::disk('public')->delete($settings->header_logo_path);
                    }
                    $settings->header_logo_path = $newPath;
                }
            }
        }

        if (! $request->boolean('remove_footer_logo') && $request->filled('footer_logo_media_id')) {
            $asset = MediaAsset::query()->find($request->integer('footer_logo_media_id'));
            if ($asset) {
                $newPath = BrandingFromMediaImporter::copyToPublicBranding($asset);
                if ($newPath !== null) {
                    if ($settings->footer_logo_path) {
                        Storage::disk('public')->delete($settings->footer_logo_path);
                    }
                    $settings->footer_logo_path = $newPath;
                }
            }
        }

        $settings->home_hero_heading = (string) ($request->input('home_hero_heading') ?? '');
        $settings->home_hero_background_type = (string) ($request->input('home_hero_background_type') ?? 'color');
        $settings->home_hero_background_color = (string) ($request->input('home_hero_background_color') ?? '#e3e3e0');
        $settings->home_hero_button1_label = (string) ($request->input('home_hero_button1_label') ?? '');
        $settings->home_hero_button1_url = (string) ($request->input('home_hero_button1_url') ?? '');
        $settings->home_hero_button2_label = (string) ($request->input('home_hero_button2_label') ?? '');
        $settings->home_hero_button2_url = (string) ($request->input('home_hero_button2_url') ?? '');

        if ($request->boolean('remove_home_hero_background')) {
            if ($settings->home_hero_background_path) {
                Storage::disk('public')->delete($settings->home_hero_background_path);
            }
            $settings->home_hero_background_path = null;
        }

        if (! $request->boolean('remove_home_hero_background') && $request->filled('home_hero_background_media_id')) {
            $asset = MediaAsset::query()->find($request->integer('home_hero_background_media_id'));
            if ($asset) {
                $newPath = BrandingFromMediaImporter::copyToPublicBranding($asset);
                if ($newPath !== null) {
                    if ($settings->home_hero_background_path) {
                        Storage::disk('public')->delete($settings->home_hero_background_path);
                    }
                    $settings->home_hero_background_path = $newPath;
                }
            }
        }

        $email = $request->input('notification_email', []);
        if (! is_array($email)) {
            $email = [];
        }
        $templates = [];
        foreach (SiteEmailTemplateService::allTemplateKeys() as $key) {
            $r = isset($email[$key]) && is_array($email[$key]) ? $email[$key] : [];
            $subj = isset($r['subject']) ? trim((string) $r['subject']) : '';
            $body = isset($r['body_html']) ? trim((string) $r['body_html']) : '';
            if (mb_strlen($subj) > 255) {
                $subj = mb_substr($subj, 0, 255);
            }
            if (mb_strlen($body) > 65000) {
                $body = mb_substr($body, 0, 65000);
            }
            $templates[$key] = [
                'subject' => $subj,
                'body_html' => $body,
            ];
        }
        $settings->notification_email_templates = $templates;

        // Toggle: when off, mail still uses .env. Credentials below are stored anyway so
        // admins can fill the form, save, then enable — previously values were only
        // written when the checkbox was checked, which looked like "SMTP not saving".
        $settings->smtp_enabled = $request->boolean('smtp_enabled');

        $host = trim((string) $request->input('smtp_host', ''));
        $settings->smtp_host = $host !== '' ? $host : null;

        $portRaw = $request->input('smtp_port');
        if ($portRaw === null || $portRaw === '') {
            $settings->smtp_port = null;
        } else {
            $settings->smtp_port = max(1, min(65535, (int) $portRaw));
        }

        $enc = strtolower((string) $request->input('smtp_encryption', 'tls'));
        $settings->smtp_encryption = in_array($enc, ['tls', 'ssl', 'none'], true) ? $enc : 'tls';

        $username = trim((string) $request->input('smtp_username', ''));
        $settings->smtp_username = $username !== '' ? $username : null;

        if ($request->filled('smtp_password')) {
            $settings->smtp_password = (string) $request->input('smtp_password');
        }

        $fromAddr = trim((string) $request->input('smtp_from_address', ''));
        $settings->smtp_from_address = $fromAddr !== '' ? $fromAddr : null;

        $fromName = trim((string) $request->input('smtp_from_name', ''));
        $settings->smtp_from_name = $fromName !== '' ? $fromName : null;

        $settings->host_registration_auto_approve = $request->boolean('host_registration_auto_approve');

        $settings->save();

        return redirect()
            ->route('admin.settings.edit')
            ->with('status', __('Settings saved.'));
    }
}
