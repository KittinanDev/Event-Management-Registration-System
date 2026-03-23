<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $location
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $start_datetime
 * @property \Illuminate\Support\Carbon|null $end_datetime
 */
class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'start_datetime',
        'end_datetime',
        'max_participants',
        'max_attendees',
        'current_attendees',
        'status',
        'image',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    // Relationships
    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>', now())
                     ->open();
    }

    // Accessors
    public function getAvailableSeatsAttribute()
    {
        $max = $this->max_attendees ?? $this->max_participants;

        if ($max === null) {
            return null;
        }

        $current = $this->current_attendees ?? 0;

        return max(0, $max - $current);
    }

    public function getConfirmedCountAttribute()
    {
        return $this->current_attendees ?? 0;
    }
}
