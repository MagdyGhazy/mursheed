<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CategoryAccommodation extends Model
{
    use HasFactory,HasTranslations;

    protected $translatable= ['name'];
    protected $guarded = [];
    
    public function accommodations()
    {
        return $this->hasMany(accommmodition::class);
    }
    public function accommodationsorder()
    {
        return $this->hasMany(OrderAccommmodition::class);
    }
}