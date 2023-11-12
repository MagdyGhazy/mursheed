<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class AttractiveLocation extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTranslations;

    protected $table = 'attractive_locations';

    protected $guarded = array();


    public $translatable = ['name', 'description'];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'country_id')->lang();
    }
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }
}
