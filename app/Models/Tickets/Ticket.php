<?php

namespace App\Models\Tickets;

use App\Models\MursheedUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['number','title','user_id','status','priority','type'];

    public function message():HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }
    public function replay():HasMany
    {
        return $this->hasMany(TicketReplay::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(MursheedUser::class);
    }

}
