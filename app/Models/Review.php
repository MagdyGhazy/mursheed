<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = "reviews";

    protected $fillable = [
        'tourist_id',
        'comment',
        'tourist_rating',
    ];

    public function reviewable()
    {
        return $this->morphTo();
    }
}
