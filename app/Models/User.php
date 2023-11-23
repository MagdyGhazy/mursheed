<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasPermissionsTrait;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = array();

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
        'password' => 'hashed',
    ];





    public function mursheed_user()
    {
        return $this->morphOne(MursheedUser::class, 'user');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasPermission()
    {
        return $this->belongsToMany(Permission::class);
    }

    //Chat
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class,'participants')->latest('last_message_id')->withPivot(['role','joined_at']);
    }
    public function sentMessages()
    {
        return $this->hasMany(Message::class,'user_id','id');
    }
    public function receivedMessages()
    {
        return $this->belongsToMany(Message::class,'recipients')->withPivot(['read_at','deleted_at']);
    }
}
