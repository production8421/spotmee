<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
            ],
        );
        $admin->syncRoles([UserRole::Administrator->value]);

        $host = User::query()->updateOrCreate(
            ['email' => 'host@example.com'],
            [
                'name' => 'Host User',
                'password' => Hash::make('password'),
            ],
        );
        $host->syncRoles([UserRole::Host->value]);

        $subscriber = User::query()->updateOrCreate(
            ['email' => 'subscriber@example.com'],
            [
                'name' => 'Subscriber User',
                'password' => Hash::make('password'),
            ],
        );
        $subscriber->syncRoles([UserRole::Subscriber->value]);
    }
}
