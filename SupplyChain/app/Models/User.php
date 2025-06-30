<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Supplier;
use App\Models\Manufacturer;
use App\Models\Wholesaler;
use App\Models\ChatMessage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    const ROLE_ADMIN = 'admin';
    const ROLE_SUPPLIER = 'supplier';
    const ROLE_WHOLESELLER = 'wholesaler';
    const ROLE_MANUFACTURER = 'manufacturer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_picture',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => 'string',
        'is_active' => 'boolean',
    ];

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class);
    }

    public function manufacturer()
    {
        return $this->hasOne(Manufacturer::class);
    }

    public function wholesaler()
    {
        return $this->hasOne(Wholesaler::class);
    }

    /**
     * Get messages sent by this user.
     */
    public function sentMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    /**
     * Get messages received by this user.
     */
    public function receivedMessages()
    {
        return $this->hasMany(ChatMessage::class, 'receiver_id');
    }

    /**
     * Get unread messages for this user.
     */
    public function unreadMessages()
    {
        return $this->receivedMessages()->where('is_read', false);
    }
}
