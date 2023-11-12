<?php

namespace App\Http\Controllers\Filter;
use Illuminate\Http\Request;

use Illuminate\Database\Query\Builder;

class SearchByCarCategory
{
    public function __construct(protected  Request $request)
    {


    }

    public function handle( $builder, \Closure $next)
    {
        return $next($builder)->when($this->request->has('car_type'),fn($query)=>$query->where('car_type',$this->request->car_type));
    }
}

