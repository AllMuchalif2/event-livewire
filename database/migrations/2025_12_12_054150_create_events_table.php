<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migrasi untuk membuat tabel events.
     * Tabel ini menyimpan data event/seminar yang dibuat oleh admin.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // Informasi dasar event
            $table->string('title')->index()->comment('Judul/nama event');
            $table->text('description')->nullable()->comment('Deskripsi lengkap event');

            // Jadwal event
            $table->date('event_date')->index()->comment('Tanggal pelaksanaan event');
            $table->time('start_time')->comment('Jam mulai event');
            $table->time('end_time')->comment('Jam selesai event');

            // Lokasi dan kapasitas
            $table->string('location')->comment('Lokasi event');
            $table->integer('capacity')->default(0)->comment('Kapasitas maksimal peserta');

            // Pembayaran dan registrasi
            $table->decimal('price', 10, 2)->nullable()->comment('Harga tiket event (NULL jika gratis)');
            $table->date('registration_deadline')->index()->comment('Batas waktu registrasi');

            // Gambar poster
            $table->string('image')->nullable()->comment('Path gambar poster event');

            // Relasi ke pembuat event (admin)
            $table->foreignId('created_by')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('ID admin yang membuat event');

            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi dengan menghapus tabel events.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
