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
            'username' => 'admin',
            'email' => 'admin@event.test',
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Password: password
            'role' => 'admin',
        ]);

        // Participant Mahasiswa - untuk testing (dengan NIM)
        \App\Models\User::create([
            'name' => 'Budi Santoso',
            'username' => '22.123.4567', // NIM format
            'email' => 'budi@student.test',
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Password: password
            'role' => 'participant',
        ]);

        // Participant Umum - untuk testing (auto-generated)
        \App\Models\User::create([
            'name' => 'John Doe',
            'username' => 'umum-001', // Auto-generated format
            'email' => 'john@public.test',
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Password: password
            'role' => 'participant',
        ]);

        $this->command->info('✅ User seeder berhasil! Users telah dibuat dengan username.');
        $this->command->table(
            ['Role', 'Username', 'Email', 'Password'],
            [
                ['admin', 'admin', 'admin@event.test', 'password'],
                ['mahasiswa', '22.123.4567', 'budi@student.test', 'password'],
                ['umum', 'umum-001', 'john@public.test', 'password'],
            ]
        );
    }
}
