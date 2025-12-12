# Sistem Manajemen Event / Seminar

Aplikasi manajemen event/seminar dengan fitur registrasi peserta, absensi berbasis QR Code, dan laporan kehadiran. Admin menggunakan Filament untuk pengelolaan, dan user biasa menggunakan Livewire untuk registrasi dan melihat QR code.

## Fitur Utama

-   ✅ **Registrasi Peserta** - User dapat mendaftar event melalui interface Livewire
-   ✅ **Absensi QR Code** - Check-in menggunakan QR code yang di-scan oleh admin
-   ✅ **Notifikasi Email** - Konfirmasi registrasi dan kehadiran via email
-   ✅ **Laporan Kehadiran** - Export laporan dalam format PDF dan Excel
-   ✅ **Panel Admin** - Dashboard Filament untuk mengelola event dan peserta

## Tech Stack

-   **Backend**: Laravel 12, Filament 4.0
-   **Frontend**: Livewire, Volt, Flux , Tailwind CSS
-   **Database**: SQLite (dapat diganti ke MySQL)
-   **Authentication**: Laravel Fortify
-   **UI Components**: Livewire Flux, Tailwind CSS
-   **QR Code**: SimpleSoftwareIO/simple-qrcode

## Rencana Implementasi

### Phase 1: Database & Models

-   [ ] Membuat database migrations
    -   [ ] Tabel `events` (data event/seminar)
    -   [ ] Tabel `participants` (data peserta)
    -   [ ] Tabel `attendances` (data kehadiran)
    -   [ ] Menambah kolom `role` ke tabel `users`
-   [ ] Membuat Eloquent models
    -   [ ] Model `Event` dengan relasi
    -   [ ] Model `Participant` dengan relasi
    -   [ ] Model `Attendance` dengan relasi
-   [ ] Update User model dengan dukungan role

### Phase 2: Authentication & Authorization

-   [ ] Konfigurasi multi-guard authentication
    -   [ ] Admin guard (Filament)
    -   [ ] User guard (Livewire)
-   [ ] Membuat middleware untuk role-based access
-   [ ] Setup policies untuk authorization

### Phase 3: Filament Admin Panel

-   [ ] Membuat Filament Resources
    -   [ ] EventResource (CRUD untuk events)
    -   [ ] ParticipantResource (kelola peserta)
    -   [ ] AttendanceResource (laporan kehadiran)
-   [ ] Membuat custom Filament pages
    -   [ ] Halaman QR Scanner untuk absensi
    -   [ ] Dashboard dengan statistik
-   [ ] Implementasi laporan kehadiran
    -   [ ] Export ke PDF
    -   [ ] Export ke Excel
    -   [ ] Filter berdasarkan event/tanggal

### Phase 4: User Interface (Livewire)

-   [ ] Membuat Livewire components
    -   [ ] Komponen daftar event
    -   [ ] Form registrasi event
    -   [ ] Dashboard user
    -   [ ] Komponen daftar registrasi saya
    -   [ ] Komponen tampilan QR code
-   [ ] Membuat Blade layouts
    -   [ ] Layout template user
    -   [ ] Komponen navigasi

### Phase 5: Sistem QR Code

-   [ ] Install paket QR code
    -   [ ] SimpleSoftwareIO/simple-qrcode untuk generate
    -   [ ] Library JavaScript untuk scanning
-   [ ] Implementasi generate QR code
    -   [ ] Generate QR code unik terenkripsi
    -   [ ] Simpan data QR di database
    -   [ ] Tampilkan QR di dashboard user
-   [ ] Implementasi scanning QR code
    -   [ ] Interface scanner admin
    -   [ ] Validasi real-time
    -   [ ] Mencegah check-in duplikat



### Phase 6: Fitur Tambahan

-   [ ] Manajemen kapasitas event
-   [ ] Fitur waiting list
-   [ ] Generate sertifikat (opsional)

### Phase 7: Testing & Verifikasi

-   [ ] Test fungsi admin panel
-   [ ] Test alur registrasi user
-   [ ] Test generate & scanning QR code
-   [ ] Test pengiriman notifikasi
-   [ ] Test generate laporan
-   [ ] Browser testing untuk responsive design

## Instalasi

```bash
# Clone repository
git clone <repository-url>
cd livewire

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Migrate database
php artisan migrate

# Build assets
npm run build

# Run development server
php artisan serve
```

## Development

```bash
# Run dengan concurrent servers
composer dev

# Atau manual
php artisan serve
php artisan queue:listen
npm run dev
```

## Testing

```bash
# Run semua tests
php artisan test

# Run specific test
php artisan test --filter=EventTest
```

## Dokumentasi Lengkap

Untuk detail implementasi lengkap, lihat [Implementation Plan](C:\Users\LENOVO.gemini\antigravity\brain\233b91af-a0bc-4a58-8264-818d0f4c5ec6\implementation_plan.md)

## License

MIT License
