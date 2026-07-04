<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Using 'password123' as a fallback if the env variable isn't set
        $password = Hash::make(env('SEEDED_USER_PASSWORD', 'password123'));

        // Pre-fetch role to sync to the admin user
        $adminRole = Role::where('name', 'admin')->first();

        $admin = User::updateOrCreate([
            'email' => 'admin@tracked.edu.ph',
        ], [
            'employee_id' => 'SYS-ADMIN-001',
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'email_verified_at' => now(),
            'password' => $password,
            'role' => 'admin',
            'current_tier' => null,
            'is_active' => true,
        ]);

        // Sync the pivot role
        if ($adminRole) {
            $admin->roles()->sync([$adminRole->id]);
        }
    }
}
