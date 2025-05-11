<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteFlight extends Model
{
    protected $fillable = [
        'user_id',
        'flight_id',
        'origin',
        'destination',
        'departure_date',
        'price',
        'airline',
        'flight_details',
        'search_params'
    ];

    protected $casts = [
        'departure_date' => 'date:Y-m-d',
        'flight_details' => 'array',
        'search_params' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}