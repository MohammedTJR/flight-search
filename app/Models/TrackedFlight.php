<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackedFlight extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'flight_number',
        'flight_data',
        'position_data',
        'last_checked_at'
    ];

    protected $casts = [
        'flight_data' => 'array',
        'position_data' => 'array',
        'last_checked_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}