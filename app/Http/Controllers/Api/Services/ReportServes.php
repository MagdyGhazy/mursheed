<?php

namespace App\Http\Controllers\Api\Services;

use App\Models\Order;

use Illuminate\Http\Request;

class ReportServes
{
    public function __construct(protected  Request $request)
    {
    }

    public function filter($request)
    {
        $filterData = Order::filter($request)->get();
        return response()->json([
            "filterData" => $filterData
        ]);
    }

   
}
