<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'provider',
        'provider_id',
        'avatar',
        'gender',
        'country',
        'currency',
        'language',
        'phone',
        'birth_date',
        'address',
        'notification_preferences',
        'travel_preferences'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'notification_preferences' => 'array',
            'travel_preferences' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime'
        ];
    }

    protected $attributes = [
        'notification_preferences' => '{"price_alerts":true,"alert_frequency":"daily"}',
        'travel_preferences' => '{"preferred_class":"economy","direct_flights":false}'
    ];
    
    /**
     * Get default notification preferences
     *
     * @return array
     */
    public function getDefaultNotificationPreferences(): array
    {
        return [
            'price_alerts' => true,
            'alert_frequency' => 'daily',
            'analytics_cookies' => false,
            'marketing_cookies' => false,
            'enable_chat' => true,
            'chat_notifications' => false
        ];
    }

    /**
     * Get default travel preferences
     *
     * @return array
     */
    public function getDefaultTravelPreferences(): array
    {
        return [
            'preferred_class' => 'economy',
            'direct_flights' => false
        ];
    }

    /**
     * Get notification preferences with defaults
     *
     * @return array
     */
    public function getNotificationPreferencesAttribute($value)
    {
        $preferences = json_decode($value, true) ?? [];
        return array_merge($this->getDefaultNotificationPreferences(), $preferences);
    }

    /**
     * Get travel preferences with defaults
     *
     * @return array
     */
    public function getTravelPreferencesAttribute($value)
    {
        $preferences = json_decode($value, true) ?? [];
        return array_merge($this->getDefaultTravelPreferences(), $preferences);
    }

    public function favoriteFlights()
    {
        return $this->hasMany(FavoriteFlight::class);
    }

    public function trackedFlights()
    {
        return $this->hasMany(TrackedFlight::class);
    }

    public function searchHistory()
    {
        return $this->hasMany(SearchHistory::class)->latest();
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
