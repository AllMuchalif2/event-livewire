<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migrasi untuk menambahkan kolom role ke tabel users.
     * Role digunakan untuk membedakan admin dan participant.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom role dengan 2 pilihan: admin dan participant
            // Default adalah participant untuk user baru
            $table->enum('role', ['admin', 'participant'])
                ->default('participant')
                ->after('password')
                ->index()
                ->comment('Role user: admin untuk panel Filament, participant untuk interface Livewire');
        });
    }

    /**
     * Batalkan migrasi dengan menghapus kolom role.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
