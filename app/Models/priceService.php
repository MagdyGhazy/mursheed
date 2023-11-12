<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class priceService extends Model
{
    use HasFactory;

    protected $guarded = array();

    public function user(): MorphTo
    {
        return $this->morphTo();
    }
    protected $appends=['state_name'];

    public function state()
    {
        return $this->belongsTo(State::class, 'city_id', 'state_id');
    }

    public function getStateNameAttribute()
    {
        return $this->state()->pluck('state')[0];
    }
}
