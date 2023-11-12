<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    protected $guarded = array();
    protected $appends=['state'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function state()
    {
        return $this->hasOne(State::class, 'state_id', 'city_id');
    }

    public function getStateAttribute()
    {
        return $this->state()->pluck('state')[0];
    }
}
