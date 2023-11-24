<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAccommmodition extends Model
{
    use HasFactory;
    protected $fillable = ['tourist_id', 'accommmodition_id', 'total_cost','price'];

    public function tourist ()
    {
        return $this->belongsTo(Tourist::class,'tourist_id','id');
    }

}
