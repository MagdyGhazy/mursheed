<?php

namespace App\Http\Controllers\Filter;
use Illuminate\Http\Request;

use Illuminate\Database\Query\Builder;

class SearchByCountry
{
    public function __construct(protected  Request $request)
    {


    }

    public function handle( $builder, \Closure $next)
    {
        return $next($builder)->when($this->request->has('country_id'),fn($query)=>$query->where('country_id',$this->request->country_id));
    }
}

