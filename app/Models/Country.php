<?php

namespace App\Models;

use App;
use App\Traits\Lang;
use App\Traits\IsDefault;
use App\Traits\Active;
use App\Traits\Sorted;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    protected $table = 'countries';
    public $timestamps = true;
    protected $guarded = ['id'];
    //protected $dateFormat = 'U';
    protected $dates = ['created_at', 'updated_at'];

    public function states()
    {
        return $this->hasMany(State::class, 'country_id', 'id');
    }
    public function scopeLang($query)
    {

        return $query->where('lang', 'like',\App::getLocale()=='sa'?'ar':'en' );
    }

}
