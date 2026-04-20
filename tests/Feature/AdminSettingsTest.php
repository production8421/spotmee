<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\ApplicationSetting;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_settings(): void
    {
        $this->get(route('admin.settings.edit'))
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_open_settings(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->assignRole(UserRole::Subscriber->value);

        $this->actingAs($user)
            ->get(route('admin.settings.edit'))
            ->assertForbidden();
    }

    public function test_administrator_can_upload_header_and_footer_logos(): void
    {
        Storage::fake('public');

        $this->seed(RoleSeeder::class);
        $admin = User::factory()->create();
        $admin->assignRole(UserRole::Administrator->value);

        $header = UploadedFile::fake()->image('header.png', 120, 40);
        $footer = UploadedFile::fake()->image('footer.png', 200, 60);

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'header_logo' => $header,
                'footer_logo' => $footer,
            ])
            ->assertRedirect(route('admin.settings.edit'))
            ->assertSessionHas('status');

        $settings = ApplicationSetting::query()->first();
        $this->assertNotNull($settings);
        $this->assertNotNull($settings->header_logo_path);
        $this->assertNotNull($settings->footer_logo_path);
        Storage::disk('public')->assertExists($settings->header_logo_path);
        Storage::disk('public')->assertExists($settings->footer_logo_path);
    }
}
