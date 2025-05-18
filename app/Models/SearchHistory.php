<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    protected $table = 'search_history';

    protected $fillable = [
        'user_id',
        'origin',
        'destination',
        'departure_date',
        'adults',
        'children',
        'infants_in_seat',
        'infants_on_lap',
        'travel_class',
        'stops'
    ];

    protected $casts = [
        'departure_date' => 'date',
        'stops' => 'boolean',
        'created_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
