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
        'airline'
    ];

    protected $casts = [
        'departure_date' => 'date:Y-m-d', // Convierte el string a Carbon
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}