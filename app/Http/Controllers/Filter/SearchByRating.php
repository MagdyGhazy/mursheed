<?php

namespace App\Http\Controllers\Filter;
use Illuminate\Http\Request;

use Illuminate\Database\Query\Builder;

class SearchByRating
{
    public function __construct(protected  Request $request)
    {


    }

    public function handle( $builder, \Closure $next)
    {
        return $next($builder)->when($this->request->has('rating'),fn($query)=>$query->where('total_rating',$this->request->rating));
    }
}

