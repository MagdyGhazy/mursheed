<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable=['content','user_id','conversation_id'];
    use HasFactory;
    public function user(): BelongsTo
    {
        return $this->belongsTo(MursheedUser::class);
    }
    public function conversations():BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }
    
}
