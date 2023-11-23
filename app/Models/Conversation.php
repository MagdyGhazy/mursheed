<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    public  function Replies(){
        return $this->hasMany(Replay::class,'conversation_id');
    }
    public  function Message(){
        return $this->hasMany(Message::class,'conversation_id');
    }
}
