<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\HostApplication;
use App\Models\User;
use App\Notifications\HostApplicationSubmitted;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HostApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_intro_page_is_shown_at_apply(): void
    {
        $response = $this->get(route('host.apply'));

        $response->assertOk();
        $response->assertSeeText(__('Become a Host'));
        $response->assertSeeText(__('How it works'));
        $response->assertSeeText(__('Step 1 of 3'));
    }

    public function test_create_form_redirects_without_accepting_terms(): void
    {
        $this->get(route('host.apply.create'))
            ->assertRedirect(route('host.apply'));
    }

    public function test_begin_redirects_to_create_when_terms_accepted(): void
    {
        $this->post(route('host.apply.begin'), [
            'terms_accepted' => '1',
        ])->assertRedirect(route('host.apply.create'));

        $this->get(route('host.apply.create'))
            ->assertOk()
            ->assertSeeText(__('Become a host'))
            ->assertSeeText(__('Step 2 of 3'));
    }

    public function test_begin_requires_terms(): void
    {
        $this->from(route('host.apply'))
            ->post(route('host.apply.begin'), [])
            ->assertRedirect(route('host.apply'))
            ->assertSessionHasErrors('terms_accepted');
    }

    public function test_guest_can_submit_host_application(): void
    {
        $this->post(route('host.apply.begin'), ['terms_accepted' => '1']);

        $response = $this->post(route('host.apply.store'), [
            'full_name' => 'Jamie Host',
            'date_of_birth' => '1990-05-15',
            'social_security_number' => '',
            'phone' => '555-0100',
            'email' => 'jamie@example.com',
            'street_address' => '123 Main St',
            'city' => 'Austin',
            'state' => 'TX',
            'postal_code' => '78701',
            'description' => '',
        ]);

        $response->assertRedirect(route('host.apply.submitted'));
        $response->assertSessionHas('host_application_submitted', true);

        $this->assertDatabaseHas('host_applications', [
            'full_name' => 'Jamie Host',
            'email' => 'jamie@example.com',
            'user_id' => null,
        ]);

        $this->assertNull(HostApplication::query()->first()?->social_security_number);
    }

    public function test_ssn_is_normalized_and_stored_encrypted(): void
    {
        $this->post(route('host.apply.begin'), ['terms_accepted' => '1']);

        $this->post(route('host.apply.store'), [
            'full_name' => 'Jamie Host',
            'date_of_birth' => '1990-05-15',
            'social_security_number' => '123-45-6789',
            'phone' => '555-0100',
            'email' => 'jamie2@example.com',
            'street_address' => '123 Main St',
            'city' => 'Austin',
            'state' => 'TX',
            'postal_code' => '78701',
            'description' => null,
        ])->assertRedirect(route('host.apply.submitted'));

        $this->assertSame('123456789', HostApplication::query()->first()->social_security_number);
    }

    public function test_submitted_page_redirects_without_session_flash(): void
    {
        $this->get(route('host.apply.submitted'))
            ->assertRedirect(route('host.apply'));
    }

    public function test_submitted_page_shows_after_successful_application(): void
    {
        $this->post(route('host.apply.begin'), ['terms_accepted' => '1']);

        $this->post(route('host.apply.store'), [
            'full_name' => 'Jamie Host',
            'date_of_birth' => '1990-05-15',
            'social_security_number' => '',
            'phone' => '555-0100',
            'email' => 'jamie3@example.com',
            'street_address' => '123 Main St',
            'city' => 'Austin',
            'state' => 'TX',
            'postal_code' => '78701',
            'description' => '',
        ]);

        $this->get(route('host.apply.submitted'))
            ->assertOk()
            ->assertSeeText(__('Registration Submitted!'))
            ->assertSeeText(__('Waiting for Admin Approval'));
    }

    public function test_terms_session_cleared_after_successful_submit(): void
    {
        $this->post(route('host.apply.begin'), ['terms_accepted' => '1']);
        $this->post(route('host.apply.store'), [
            'full_name' => 'Jamie Host',
            'date_of_birth' => '1990-05-15',
            'social_security_number' => '',
            'phone' => '555-0100',
            'email' => 'jamie4@example.com',
            'street_address' => '123 Main St',
            'city' => 'Austin',
            'state' => 'TX',
            'postal_code' => '78701',
            'description' => '',
        ]);

        $this->get(route('host.apply.create'))
            ->assertRedirect(route('host.apply'));
    }

    public function test_administrators_receive_database_notification_when_application_submitted(): void
    {
        $this->seed(RoleSeeder::class);
        $admin = User::factory()->create(['email' => 'admin-notify-test@example.com']);
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
        ])->assertRedirect(route('host.apply.submitted'));

        $admin->refresh();
        $this->assertCount(1, $admin->notifications);
        $row = $admin->notifications->first();
        $this->assertSame(HostApplicationSubmitted::class, $row->type);
        $this->assertStringContainsString('Jamie Host', (string) ($row->data['body'] ?? ''));
        $this->assertArrayHasKey('url', $row->data);
        $this->assertStringContainsString('/admin/host-applications/', (string) $row->data['url']);
        $this->assertNull($row->read_at);
    }

    public function test_administrator_can_mark_notification_as_read(): void
    {
        $this->seed(RoleSeeder::class);
        $admin = User::factory()->create();
        $admin->assignRole(UserRole::Administrator->value);

        $this->post(route('host.apply.begin'), ['terms_accepted' => '1']);
        $this->post(route('host.apply.store'), [
            'full_name' => 'Pat Host',
            'date_of_birth' => '1991-01-10',
            'social_security_number' => '',
            'phone' => '555-0199',
            'email' => 'pat@example.com',
            'street_address' => '9 Oak Rd',
            'city' => 'Dallas',
            'state' => 'TX',
            'postal_code' => '75201',
            'description' => '',
        ]);

        $notificationId = $admin->fresh()->notifications->first()->id;

        $this->actingAs($admin)
            ->post(route('notifications.read', $notificationId))
            ->assertRedirect();

        $this->assertNotNull($admin->notifications()->whereKey($notificationId)->value('read_at'));
    }
}
