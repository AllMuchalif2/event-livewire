<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Participant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'event_id',
        'user_id',
        'name',
        'email',
        'phone',
        'type',
        'payment_amount',
        'payment_proof',
        'payment_status',
        'registration_number',
        'qr_code',
        'status',
        'registered_at',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'payment_amount' => 'decimal:2',
            'registered_at' => 'datetime',
        ];
    }

    /**
     * Get the event this participant is registered for.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user (if registered user).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attendance record for this participant.
     */
    public function attendance(): HasOne
    {
        return $this->hasOne(Attendance::class);
    }

    /**
     * Check if participant has checked in.
     */
    public function hasAttended(): bool
    {
        return $this->attendance()->exists();
    }

    /**
     * Check if payment has been verified.
     */
    public function isPaymentVerified(): bool
    {
        return $this->payment_status === 'verified';
    }

    /**
     * Check if payment is still pending.
     */
    public function isPaymentPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Check if payment was rejected.
     */
    public function isPaymentRejected(): bool
    {
        return $this->payment_status === 'rejected';
    }

    /**
     * Check if this is a mahasiswa participant.
     */
    public function isMahasiswa(): bool
    {
        return $this->type === 'mahasiswa';
    }

    /**
     * Check if this is an umum participant.
     */
    public function isUmum(): bool
    {
        return $this->type === 'umum';
    }

    /**
     * Get username dari user relationship (NIM atau umum-XXX).
     */
    public function getUsernameAttribute(): ?string
    {
        return $this->user?->username;
    }

    /**
     * Accessor untuk display payment status dalam bahasa Indonesia.
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
            default => $this->payment_status,
        };
    }

    /**
     * Accessor untuk display status dalam bahasa Indonesia.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    /**
     * Accessor untuk display type dalam bahasa Indonesia.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'mahasiswa' => 'Mahasiswa',
            'umum' => 'Umum',
            default => $this->type,
        };
    }
}
