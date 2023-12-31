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


    public function profits($request)
    {
        if ( Order::first() == null)
        {
            return response()->json([
                'message' => "no orders",
                'profits' => [
                    'all_profits' => 0,
                    'drivers_profits' => 0,
                    'guides_profits' => 0,
                ],
            ]);

        }

        $start_date = $request->has('start_date')?  $request->start_date : Order::first()->created_at->format('Y-m-d');
        $end_date = $request->has('end_date')?   Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->addDays(1)->format('Y-m-d');


        $allProfits = $request->country_id == null ? $this->allProfits($start_date, $end_date) : $this->allCountryProfits($start_date, $end_date, $request->country_id);
        $driversProfits = $request->country_id == null ? $this->allProfitsWithDriver($start_date, $end_date) : $this->allCountryProfitsWithDriver($start_date, $end_date, $request->country_id);
        $guidesProfits = $request->country_id == null ? $this->allProfitsWithGuides($start_date, $end_date) :  $this->allCountryProfitsWithGuides($start_date, $end_date, $request->country_id);

        return response()->json([
            'message' => "success",
            'profits' => [
                'all_profits' => round(array_sum($allProfits),2),
                'drivers_profits' => round(array_sum($driversProfits),2),
                'guides_profits' => round(array_sum($guidesProfits),2),
            ],
        ]);

    }
    public function allProfits($start_date, $end_date)
    {
        return Order::whereBetween('created_at', [$start_date, $end_date])->get()
            ->map(function ($profits) {
                $allProfits = 0 ;
                $allProfits += round($profits['profit'],2);

                return $allProfits;
            })
            ->toArray();
    }

    public function allCountryProfits($start_date, $end_date,$country_id)
    {
        return Order::whereBetween('created_at', [$start_date, $end_date])->where('country_id',$country_id)->get()
            ->map(function ($profits) {
                $allProfits = 0 ;
                $allProfits += round($profits['profit'],2);

                return $allProfits;
            })
            ->toArray();
    }


    public function allProfitsWithDriver($start_date, $end_date)
    {
        return Order::whereBetween('created_at', [$start_date, $end_date])->where('user_type','App\Models\Driver')->get()
            ->map(function ($profits) {
                $allProfits = 0 ;
                $allProfits += round($profits['profit'],2);

                return $allProfits;
            })
            ->toArray();
    }

    public function allCountryProfitsWithDriver($start_date, $end_date,$country_id)
    {
        return Order::whereBetween('created_at', [$start_date, $end_date])->where('user_type','App\Models\Driver')->where('country_id',$country_id)->get()
            ->map(function ($profits) {
                $allProfits = 0 ;
                $allProfits += round($profits['profit'],2);

                return $allProfits;
            })
            ->toArray();
    }

    public function allProfitsWithGuides($start_date, $end_date)
    {
        return Order::whereBetween('created_at', [$start_date, $end_date])->where('user_type','App\Models\Guides')->get()
            ->map(function ($profits) {
                $allProfits = 0 ;
                $allProfits += round($profits['profit'],2);

                return $allProfits;
            })
            ->toArray();
    }

    public function allCountryProfitsWithGuides($start_date, $end_date,$country_id)
    {
        return Order::whereBetween('created_at', [$start_date, $end_date])->where('user_type','App\Models\Guides')->where('country_id',$country_id)->get()
            ->map(function ($profits) {
                $allProfits = 0 ;
                $allProfits += round($profits['profit'],2);

                return $allProfits;
            })
            ->toArray();
    }
}
