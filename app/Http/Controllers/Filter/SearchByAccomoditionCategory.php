<?php

namespace App\Http\Controllers\Filter;
use Illuminate\Http\Request;

use Illuminate\Database\Query\Builder;

class SearchByAccomoditionCategory
{
    public function __construct(protected  Request $request)
    {


    }

    public function handle( $builder, \Closure $next)
    {
        return $next($builder)->when($this->request->has('category_id'),fn($query)=>$query->where('category_accommodations_id',$this->request->category_id));
    }
}

