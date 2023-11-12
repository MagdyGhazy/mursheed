<?php

namespace App\Models;

use App\Traits\IsDefault;
use App\Traits\Active;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{


    protected $table = 'languages';

    public function scopeSorted($query)
    {
        return $query->orderBy('lang');
    }


}
