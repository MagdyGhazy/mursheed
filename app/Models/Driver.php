<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Notifications\Notifiable;

class Driver extends Authenticatable implements HasMedia
{
    use HasFactory, HasApiTokens, InteractsWithMedia, Notifiable;

    protected $guarded = array();
    public $timestamps = false;



    public function languagesable()
    {
        return $this->morphMany(Languagesable::class, 'languagesable')->with('language');
    }
    public function order()
    {
        return $this->morphMany(Order::class, 'user');
    }
    public function mursheed_user(): MorphOne
    {
        return $this->morphOne(MursheedUser::class, 'user');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'service');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'country_id')->lang();
    }
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }
    public function getStateNameAttribute()
    {
        return $this->state()->pluck('state')[0];
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

    public function priceServices()
    {
        return $this->morphMany(priceService::class, 'user');
    }

    public function delete()
    {
        $this->mursheed_user()->delete();

        return parent::delete();
    }
}