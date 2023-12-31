<?php

namespace App\Models\Tickets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketReplay extends Model
{
    use HasFactory;
    protected $fillable = ['content','ticket_id'];
    public $translatable = ['content'];


    public function ticket():BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
