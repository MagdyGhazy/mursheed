<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable=['content','user_id','conversation_id'];
    use HasFactory;
    public function mursheedUsers()
    {
        return $this->belongsTo(MursheedUser::class);
    }
    public function conversations()
    {
        return $this->belongsTo(Conversation::class);
    }
}
