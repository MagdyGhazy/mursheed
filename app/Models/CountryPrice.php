<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryPrice extends Model
{
    use HasFactory;


    protected $table = "country_price";

    protected $fillable = [
        'country_id',
        'price',
        'fees',
        'tax',
        'status',
    ];


    protected $appends=['country_name'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function getCountryNameAttribute()
    {
        return $this->country->country;
    }
}
