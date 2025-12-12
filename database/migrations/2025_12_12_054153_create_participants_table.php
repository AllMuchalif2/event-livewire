<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migrasi untuk membuat tabel participants.
     * Tabel ini menyimpan data registrasi peserta event.
     */
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();

            // Relasi ke event dan user
            $table->foreignId('event_id')
                ->constrained('events')
                ->onDelete('cascade')
                ->comment('ID event yang diikuti');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->comment('ID user (jika user sudah terdaftar)');

            // Data peserta
            $table->string('name')->index()->comment('Nama lengkap peserta');
            $table->string('email')->index()->comment('Email peserta');
            $table->string('phone', 20)->comment('Nomor telepon peserta');
            $table->enum('type', ['mahasiswa', 'umum'])
                ->default('umum')
                ->index()
                ->comment('Jenis peserta: mahasiswa atau umum');

            // Data pembayaran
            $table->decimal('payment_amount', 10, 2)
                ->nullable()
                ->comment('Jumlah pembayaran (NULL jika event gratis)');

            $table->string('payment_proof')
                ->nullable()
                ->comment('Path file bukti pembayaran');

            $table->enum('payment_status', ['pending', 'verified', 'rejected'])
                ->default('pending')
                ->index()
                ->comment('Status verifikasi pembayaran');

            // Nomor registrasi dan QR code
            $table->string('registration_number', 50)
                ->unique()
                ->comment('Nomor registrasi unik untuk peserta');

            $table->text('qr_code')->comment('Encrypted QR code data');

            // Status registrasi
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])
                ->default('confirmed')
                ->index()
                ->comment('Status registrasi peserta');

            $table->timestamp('registered_at')->useCurrent()->comment('Waktu pendaftaran');
            $table->timestamps();

            // Composite index untuk mencegah duplikasi registrasi
            $table->unique(['event_id', 'email'], 'unique_participant_per_event');
        });
    }

    /**
     * Batalkan migrasi dengan menghapus tabel participants.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
