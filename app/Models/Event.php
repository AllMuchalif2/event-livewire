<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'capacity',
        'price',
        'registration_deadline',
        'image',
        'created_by',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'registration_deadline' => 'date',
            'price' => 'decimal:2',
        ];
    }

    /**
     * Get the user (admin) who created this event.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all participants for this event.
     */
    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    /**
     * Get all attendances for this event.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get kursi yang masih tersedia.
     */
    public function getAvailableSeatsAttribute(): int
    {
        return max(0, $this->capacity - $this->participants()->count());
    }

    /**
     * Check apakah event sudah penuh.
     */
    public function isFull(): bool
    {
        return $this->participants()->count() >= $this->capacity;
    }

    /**
     * Check apakah registrasi masih dibuka.
     */
    public function isRegistrationOpen(): bool
    {
        return now()->lte($this->registration_deadline);
    }

    /**
     * Get status event (upcoming, ongoing, completed).
     */
    public function getStatusAttribute(): string
    {
        $now = now();
        $eventDate = $this->event_date;

        if ($now->lt($eventDate)) {
            return 'upcoming';
        } elseif ($now->isSameDay($eventDate)) {
            return 'ongoing';
        } else {
            return 'completed';
        }
    }

    /**
     * Check apakah event gratis.
     */
    public function isFree(): bool
    {
        return is_null($this->price) || $this->price == 0;
    }

    /**
     * Get tingkat kehadiran (attendance rate).
     */
    public function getAttendanceRateAttribute(): float
    {
        $totalParticipants = $this->participants()->count();

        if ($totalParticipants === 0) {
            return 0;
        }

        $totalAttendances = $this->attendances()->count();

        return ($totalAttendances / $totalParticipants) * 100;
    }
}
