<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Tourist extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, InteractsWithMedia ,Notifiable;
    protected $guarded = array();

    public function languagesable()
    {
        return $this->morphMany(Languagesable::class, 'languagesable');
    }
    public function mursheed_user()
    {
        return $this->morphOne(MursheedUser::class, 'user');
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

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

    public function delete()
    {
        $this->mursheed_user()->delete();

        return parent::delete();
    }
    public function accommmadationOrder()
    {
        return $this->hasMany(OrderAccommmodition::class);
    }

}
