<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed database dengan user admin dan participant untuk testing.
     */
    public function run(): void
    {
        // Admin default - untuk akses Filament panel
        \App\Models\User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@event.test',
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Password: password
            'role' => 'admin',
        ]);

        // Participant default - untuk testing registrasi event
        \App\Models\User::create([
            'name' => 'John Doe',
            'email' => 'participant@event.test',
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Password: password
            'role' => 'participant',
        ]);

        $this->command->info('✅ User seeder berhasil! Admin dan Participant telah dibuat.');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['admin', 'admin@event.test', 'password'],
                ['participant', 'participant@event.test', 'password'],
            ]
        );
    }
}
