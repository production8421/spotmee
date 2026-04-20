<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateApplicationSettingsRequest;
use App\Models\ApplicationSetting;
use App\Models\MediaAsset;
use App\Services\BrandingFromMediaImporter;
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

        $settings->save();

        return redirect()
            ->route('admin.settings.edit')
            ->with('status', __('Settings saved.'));
    }
}
