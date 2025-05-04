<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    protected $fillable = [
        'key',
        'service',
        'is_valid',
        'remaining_requests',
        'last_used_at',
        'invalid_at'
    ];

    protected $casts = [
        'is_valid' => 'boolean',
        'last_used_at' => 'datetime',
        'invalid_at' => 'datetime',
    ];

    public function scopeValid($query)
    {
        return $query->where('is_valid', true);
    }

    public function scopeForService($query, $service)
    {
        return $query->where('service', $service);
    }

    public function markAsInvalid()
    {
        $this->update([
            'is_valid' => false,
            'invalid_at' => now(),
        ]);
    }

    public function markAsValid()
    {
        $this->update([
            'is_valid' => true,
            'invalid_at' => null,
        ]);
    }

    public function hasExpiredInvalidation()
    {
        if (!$this->invalid_at) {
            return false;
        }

        return $this->invalid_at->diffInHours(now()) >= 24; // 24 horas de invalidación
    }
}