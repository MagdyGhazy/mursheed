<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Languagesable extends Model
{
    use HasFactory;
    protected $table="languagesable";
    protected $guarded=array();

    public function languagesable (){
        return $this->morphTo();
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
