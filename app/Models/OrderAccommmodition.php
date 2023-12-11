<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAccommmodition extends Model
{
    use HasFactory;
    protected $fillable = [
        'tourist_id',
        'accommmodition_id',
        'total_cost', 'price',
        'end_date', 'start_date',
        'category_id'
    ];

    public function tourist()
    {
        return $this->belongsTo(Tourist::class, 'tourist_id', 'id');
    }
 
        public function category()
        {
            return $this->belongsTo(CategoryAccommodation::class,'category_id','id');
        }
   
}
