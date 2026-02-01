<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>', now())
                     ->published();
    }

    // Accessors
    public function getAvailableSeatsAttribute()
    {
        if ($this->max_participants === null) {
            return null;
        }

        $registeredCount = $this->registrations()
            ->where('status', 'confirmed')
            ->count();

        return max(0, $this->max_participants - $registeredCount);
    }

    public function getConfirmedCountAttribute()
    {
        return $this->registrations()
            ->where('status', 'confirmed')
            ->count();
    }
}
