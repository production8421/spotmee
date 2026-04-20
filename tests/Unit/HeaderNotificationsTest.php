<?php

namespace Tests\Unit;

use App\Enums\HostApplicationStatus;
use App\Enums\UserRole;
use App\Models\HostApplication;
use App\Models\User;
use App\Services\Admin\HostApplicationApprovalService;
use App\Services\HeaderNotifications;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class HeaderNotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_pending_host_application_notifications_are_visible(): void
    {
        Mail::fake();
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole(UserRole::Administrator->value);

        $this->post(route('host.apply.begin'), ['terms_accepted' => '1']);
        $this->post(route('host.apply.store'), [
            'full_name' => 'Jamie Host',
            'date_of_birth' => '1990-05-15',
            'social_security_number' => '',
            'phone' => '555-0100',
            'email' => 'jamie-notify@example.com',
            'street_address' => '123 Main St',
            'city' => 'Austin',
            'state' => 'TX',
            'postal_code' => '78701',
            'description' => 'Test note',
        ]);

        $admin->refresh();
        $this->assertCount(1, HeaderNotifications::visibleFor($admin));
        $this->assertSame(1, HeaderNotifications::unreadVisibleCount($admin));

        $application = HostApplication::query()->where('email', 'jamie-notify@example.com')->first();
        $this->assertNotNull($application);
        $this->assertTrue($application->status === HostApplicationStatus::Pending);

        $this->actingAs($admin);
        app(HostApplicationApprovalService::class)->approve($application, $admin);

        $admin->refresh();
        $this->assertCount(0, HeaderNotifications::visibleFor($admin));
        $this->assertSame(0, HeaderNotifications::unreadVisibleCount($admin));
    }
}
