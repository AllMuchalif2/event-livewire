<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username', // NIM atau umum-XXX
        'password',
        'role', // Tambah role ke fillable
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Cek apakah user ini adalah admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user ini adalah participant (user biasa).
     */
    public function isParticipant(): bool
    {
        return $this->role === 'participant';
    }

    /**
     * Relasi ke events yang dibuat oleh user ini (hanya untuk admin).
     */
    public function createdEvents()
    {
        return $this->hasMany(\App\Models\Event::class, 'created_by');
    }

    /**
     * Relasi ke participants - registrasi event yang dilakukan user ini.
     */
    public function participants()
    {
        return $this->hasMany(\App\Models\Participant::class);
    }

    /**
     * Extend Filament canAccessPanel untuk hanya allow admin.
     */
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return $this->isAdmin();
    }
}
