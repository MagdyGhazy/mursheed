<?php

namespace App\Models\Tickets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketMessage extends Model
{
    use HasFactory;
    protected $fillable = ['content','ticket_id'];

    public function ticket():BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
