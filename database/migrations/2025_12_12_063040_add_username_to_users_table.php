<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migrasi untuk menambahkan kolom username ke tabel users.
     * Username untuk:
     * - Mahasiswa: NIM (format: 00.000.0000)
     * - Umum: Auto-generated (format: umum-001, umum-002, dst)
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom username setelah email
            $table->string('username', 50)
                ->unique()
                ->after('email')
                ->nullable() // Nullable dulu untuk existing users
                ->comment('Username: NIM untuk mahasiswa, umum-XXX untuk umum');
        });
    }

    /**
     * Batalkan migrasi dengan menghapus kolom username.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
