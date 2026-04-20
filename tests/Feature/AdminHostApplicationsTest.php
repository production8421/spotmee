<?php

namespace Tests\Feature;

use App\Enums\HostApplicationStatus;
use App\Enums\UserRole;
use App\Mail\HostApprovedMail;
use App\Models\HostApplication;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminHostApplicationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_view_host_applications_list(): void
    {
        $this->get(route('admin.host-applications.index'))
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_view_host_applications_list(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->assignRole(UserRole::Subscriber->value);

        $this->actingAs($user)
            ->get(route('admin.host-applications.index'))
            ->assertForbidden();
    }

    public function test_non_admin_cannot_approve_host_application(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->assignRole(UserRole::Subscriber->value);

        $application = HostApplication::query()->create([
            'user_id' => null,
            'full_name' => 'Casey Applicant',
            'date_of_birth' => '1988-03-20',
            'social_security_number' => null,
            'phone' => '555-2000',
            'email' => 'casey@example.com',
            'street_address' => '77 Pine Ave',
            'city' => 'Houston',
            'state' => 'TX',
            'postal_code' => '77002',
            'description' => '',
        ]);

        $this->actingAs($user)
            ->post(route('admin.host-applications.approve', $application))
            ->assertForbidden();
    }

    public function test_administrator_can_view_host_applications_list_and_details(): void
    {
        $this->seed(RoleSeeder::class);
        $admin = User::factory()->create();
        $admin->assignRole(UserRole::Administrator->value);

        $application = HostApplication::query()->create([
            'user_id' => null,
            'full_name' => 'Casey Applicant',
            'date_of_birth' => '1988-03-20',
            'social_security_number' => null,
            'phone' => '555-2000',
            'email' => 'casey@example.com',
            'street_address' => '77 Pine Ave',
            'city' => 'Houston',
            'state' => 'TX',
            'postal_code' => '77002',
            'description' => 'I would like to host events.',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.host-applications.index'))
            ->assertOk()
            ->assertSeeText('Casey Applicant')
            ->assertSeeText('casey@example.com');

        $this->actingAs($admin)
            ->get(route('admin.host-applications.show', $application))
            ->assertOk()
            ->assertSeeText('Casey Applicant')
            ->assertSeeText('77 Pine Ave')
            ->assertSeeText('I would like to host events.');
    }

    public function test_administrator_can_approve_host_application_from_list(): void
    {
        Mail::fake();

        $this->seed(RoleSeeder::class);
        $admin = User::factory()->create();
        $admin->assignRole(UserRole::Administrator->value);

        $applicant = User::factory()->create(['email' => 'future-host@example.com']);
        $applicant->assignRole(UserRole::Subscriber->value);

        $application = HostApplication::query()->create([
            'user_id' => $applicant->id,
            'full_name' => 'Future Host',
            'date_of_birth' => '1990-01-01',
            'social_security_number' => null,
            'phone' => '555-3000',
            'email' => 'future-host@example.com',
            'street_address' => '1 Main St',
            'city' => 'Austin',
            'state' => 'TX',
            'postal_code' => '78701',
            'description' => '',
        ]);

        $this->actingAs($admin)
            ->from(route('admin.host-applications.index'))
            ->post(route('admin.host-applications.approve', $application))
            ->assertRedirect(route('admin.host-applications.index'));

        $application->refresh();
        $this->assertTrue($application->isApproved());
        $this->assertSame(HostApplicationStatus::Approved, $application->status);
        $this->assertSame($admin->id, $application->approved_by);
        $this->assertNotNull($application->approved_at);

        $applicant->refresh();
        $this->assertTrue($applicant->hasRole(UserRole::Host->value));

        Mail::assertSent(HostApprovedMail::class, function (HostApprovedMail $mail) use ($applicant) {
            if (! $mail->hasTo($applicant->email)) {
                return false;
            }

            return Hash::check($mail->plainPassword, $applicant->password);
        });
    }

    public function test_approval_creates_user_and_sends_credentials_when_applicant_has_no_account(): void
    {
        Mail::fake();

        $this->seed(RoleSeeder::class);
        $admin = User::factory()->create();
        $admin->assignRole(UserRole::Administrator->value);

        $application = HostApplication::query()->create([
            'user_id' => null,
            'full_name' => 'Brand New Host',
            'date_of_birth' => '1992-06-15',
            'social_security_number' => null,
            'phone' => '555-6000',
            'email' => 'brand-new-host@example.com',
            'street_address' => '50 Elm St',
            'city' => 'San Antonio',
            'state' => 'TX',
            'postal_code' => '78201',
            'description' => '',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.host-applications.approve', $application))
            ->assertRedirect();

        $application->refresh();
        $this->assertNotNull($application->user_id);
        $this->assertTrue($application->isApproved());

        $host = User::query()->where('email', 'brand-new-host@example.com')->first();
        $this->assertNotNull($host);
        $this->assertSame('Brand New Host', $host->name);
        $this->assertTrue($host->hasRole(UserRole::Host->value));
        $this->assertNotNull($host->email_verified_at);

        Mail::assertSent(HostApprovedMail::class, function (HostApprovedMail $mail) use ($host) {
            return $mail->hasTo($host->email) && Hash::check($mail->plainPassword, $host->password);
        });
    }

    public function test_approving_already_approved_application_is_idempotent(): void
    {
        Mail::fake();

        $this->seed(RoleSeeder::class);
        $admin = User::factory()->create();
        $admin->assignRole(UserRole::Administrator->value);

        $application = HostApplication::query()->create([
            'user_id' => null,
            'full_name' => 'Done Applicant',
            'date_of_birth' => '1991-02-02',
            'social_security_number' => null,
            'phone' => '555-4000',
            'email' => 'done@example.com',
            'street_address' => '2 Oak',
            'city' => 'Dallas',
            'state' => 'TX',
            'postal_code' => '75201',
            'description' => '',
            'status' => HostApplicationStatus::Approved,
            'approved_at' => now(),
            'approved_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.host-applications.show', $application))
            ->post(route('admin.host-applications.approve', $application))
            ->assertRedirect(route('admin.host-applications.show', $application))
            ->assertSessionHas('status');

        Mail::assertNothingSent();
    }
}
