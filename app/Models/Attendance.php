<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'participant_id',
        'event_id',
        'checked_in_by',
        'checked_in_at',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'checked_in_at' => 'datetime',
        ];
    }

    /**
     * Get the participant who checked in.
     */
    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    /**
     * Get the event this attendance is for.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the admin who scanned/checked in this participant.
     */
    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    /**
     * Get formatted check-in time.
     */
    public function getFormattedCheckedInAtAttribute(): string
    {
        return $this->checked_in_at->format('d M Y, H:i');
    }

    /**
     * Check if checked in on time (before event end time).
     */
    public function isOnTime(): bool
    {
        if (!$this->event) {
            return true;
        }

        $eventEndTime = \Carbon\Carbon::parse($this->event->event_date->format('Y-m-d') . ' ' . $this->event->end_time);

        return $this->checked_in_at->lte($eventEndTime);
    }

    /**
     * Check if this is a late check-in.
     */
    public function isLate(): bool
    {
        return !$this->isOnTime();
    }
}
