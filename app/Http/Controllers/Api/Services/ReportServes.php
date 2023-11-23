<?php

namespace App\Http\Controllers\Api\Services;

use App\Models\Order;

use Carbon\Carbon;
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

    public function allProfits($request,$country_id)
    {
//        $results = YourModel::whereBetween('your_date_column', [$startDate, $endDate])->get();

//        if ($request->has('start_date')) {
//            $allProfits = Order::where('country_id',$country_id)->where('created_at', '>=', $request->start_date)->get()
//                ->map(function ($profits) {
//                    $allProfits = 0 ;
//                    $allProfits += $profits['profit'];
//                    return $allProfits;
//                })
//                ->toArray();
//            return response()->json([
//                "All_Profits" => array_sum($allProfits)
//            ]);
//        }
//
//        // Check if an end_date is provided in the request
//        if ($request->has('end_date')) {
//            $allProfits = Order::where('country_id',$country_id)->where('created_at', '<=', $request->end_date)->get()
//                ->map(function ($profits) {
//                    $allProfits = 0 ;
//                    $allProfits += $profits['profit'];
//                    return $allProfits;
//                })
//                ->toArray();
//            return response()->json([
//                "All_Profits" => array_sum($allProfits)
//            ]);
//        }




        $start_date = $request->has('start_date')?  $request->start_date : Order::first()->created_at->format('Y-m-d');
        $end_date = $request->has('end_date')?   Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->addDays(1)->format('Y-m-d');

        $allProfits = Order::whereBetween('created_at', [$start_date, $end_date])->where('country_id',$country_id)->get()
            ->map(function ($profits) {
                $allProfits = 0 ;
                $allProfits += $profits['profit'];

                return $allProfits;
            })
            ->toArray();

        return response()->json([
            "All_Profits" => array_sum($allProfits),
        ]);
    }



}
