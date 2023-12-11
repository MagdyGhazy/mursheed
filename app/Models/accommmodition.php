<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class accommmodition extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia,HasTranslations;
    protected $guarded=array();
    protected $table='accommodations';

    public $translatable = ['name','owner_info','description','address'];


    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'country_id')->lang();
    }
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }
    public function category()
    {
        return $this->belongsTo(CategoryAccommodation::class,'category_accommodations_id','id');
    }

    public function  accommmoditionorder()
    {

        return $this->hasMany(OrderAccommmodition::class);
    }
//    protected $casts = [
//        'aval_status' => 'int',
//        'info_status' => 'int',
//    ];

//
//    protected function aval_status(): Attribute
//    {
//        return Attribute::make(
//            set: fn (string $value) => $value?1:0,
//        );
//    }
//    protected function aval_info(): Attribute
//    {
//        return Attribute::make(
//            set: fn (string $value) => $value?1:0,
//        );
//    }
}