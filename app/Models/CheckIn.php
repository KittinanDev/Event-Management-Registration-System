<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'checked_in_at',
        'checked_in_by',
    ];

    protected $table = 'check_ins';

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    // Relationships
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function checkedInBy()
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }
}
