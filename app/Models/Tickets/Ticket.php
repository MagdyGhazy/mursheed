<?php

namespace App\Models\Tickets;

use App\Enums\TicketStatusEnum;
use App\Models\MursheedUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['number', 'title', 'user_id', 'status', 'priority', 'type'];
    public $translatable = ['title'];
    protected $casts = ['status' => TicketStatusEnum::class];


    public function message(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }
    public function replay(): HasMany
    {
        return $this->hasMany(TicketReplay::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(MursheedUser::class)->select(['id', 'user_type', 'user_id']);
    }
}
