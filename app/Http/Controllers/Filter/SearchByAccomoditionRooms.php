<?php

namespace App\Http\Controllers\Filter;
use Illuminate\Http\Request;

use Illuminate\Database\Query\Builder;

class SearchByAccomoditionRooms
{
    public function __construct(protected  Request $request)
    {


    }

    public function handle( $builder, \Closure $next)
    {
        return $next($builder)->when($this->request->has('rooms'),fn($query)=>$query->where('rooms',$this->request->rooms));
    }
}


