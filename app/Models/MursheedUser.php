<?php

namespace App\Models;

use App\Models\Tickets\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MursheedUser extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'mursheed_users';

    protected $guarded = [];


    public function user()
    {
        return $this->morphTo();
    }

    public function sendMessages()
    {
        return $this->hasMany(Message::class, 'user_id');
    }
    public function languageesable()
    {
        return $this->hasMany(Languagesable::class, 'languagesable_id', 'id');
    }
}
