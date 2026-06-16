<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory\UserFactory */
    use HasFactory, Notifiable;

    protected $table = 'users';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'guest_name',
        'first_name',
        'last_name',
        'email',
        'username',
        'password',
        'user_type',
        'phone',
        'connection',
        'core_group',
        'specific_relationship',
        'profile_pic',
        'rsvp_status',
        'connection_id',
        'core_group_id',
        'address',
        'city',
        'state',
        'zip',
        'phone_email',
        'mobile',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Map legacy guest_name to name for Breeze compatibility.
     */
    public function getNameAttribute(): string
    {
        if (!empty($this->attributes['name'] ?? null)) {
            return $this->attributes['name'];
        }
        $name = trim(($this->guest_name ?? '') . ' ' . ($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
        return $name !== '' ? $name : 'Guest';
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->user_type === 'admin';
    }
}
