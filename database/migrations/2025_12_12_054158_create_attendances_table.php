<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migrasi untuk membuat tabel attendances.
     * Tabel ini menyimpan data kehadiran peserta melalui check-in QR code.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            // Relasi ke participant (unique - satu peserta hanya bisa check-in 1x)
            $table->foreignId('participant_id')
                ->unique()
                ->constrained('participants')
                ->onDelete('cascade')
                ->comment('ID peserta yang check-in (unique untuk prevent double check-in)');

            // Relasi ke event
            $table->foreignId('event_id')
                ->index()
                ->constrained('events')
                ->onDelete('cascade')
                ->comment('ID event yang di-attend');

            // Relasi ke admin yang melakukan scan
            $table->foreignId('checked_in_by')
                ->constrained('users')
                ->onDelete('restrict')
                ->comment('ID admin yang scan QR code');

            // Waktu check-in
            $table->timestamp('checked_in_at')
                ->useCurrent()
                ->index()
                ->comment('Waktu check-in dilakukan');

            // Catatan tambahan
            $table->text('notes')
                ->nullable()
                ->comment('Catatan tambahan dari admin');

            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi dengan menghapus tabel attendances.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
