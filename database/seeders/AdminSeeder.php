<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Creates (or updates) the first admin. Set ADMIN_EMAIL / ADMIN_PASSWORD in .env;
     * the password has no default outside local development.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@roamr.test');
        $password = env('ADMIN_PASSWORD', app()->isLocal() ? 'password' : null);

        if (! $password) {
            $this->command?->error('Set ADMIN_PASSWORD in .env before seeding an admin outside local development.');

            return;
        }

        $admin = User::firstOrNew(['email' => $email]);
        $admin->forceFill([
            'name' => $admin->name ?: 'ROAMR Admin',
            'password' => $password,
            'role' => UserRole::Admin,
            'email_verified_at' => $admin->email_verified_at ?? now(),
        ])->save();
    }
}
