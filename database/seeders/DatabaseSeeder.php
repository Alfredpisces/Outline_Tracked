<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class, // Added this to ensure roles exist first
            UserSeeder::class,

        ]);
    }
}
