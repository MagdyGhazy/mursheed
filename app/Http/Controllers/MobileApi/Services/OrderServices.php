<?php

namespace App\Http\Controllers\MobileApi\Services;

use App\Http\Controllers\MobileApi\Repository\OrderRepository;
use App\Models\CountryPrice;
use App\Models\State;

class OrderServices
{
    protected $states;
    protected $country_price;
    protected $model;
    protected $cities;
    protected $cost;
    protected $sub_total;

    public function __construct($country_id,$model)
    {
        $this->states = State::all(); // use collection to reduce queries
        $this->country_price=CountryPrice::where('country_id',$country_id)->first();
        $this->model= $model; // price in city table price services
    }


    function distance( $lat1, $lon1, $lat2, $lon2) // calculate the distance between two lat and long in km
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;

            return ($miles * 1.609344);

        }
    }


    public function calculateRoutingForCities($cities, $index = 0 , $cost = 0)
    {
//        return count($cities);
//        $index = 1 , $cost = 151.00;

        if ($index >= count($cities)) {  // base case
//            return ['cities'=>$cities, 'cost' => $cost+ $this->country_price->fees + $this->country_price->tax  ];
            $this->cities = $cities;
            $this->sub_total = $cost;
            $total_cost = $cost + ( $cost * ($this->country_price->tax/100) + $cost * ($this->country_price->fees/100) );
            $this->cost = $total_cost;

            return $this;
        }

        $cities[$index]['price_city'] = $this->model->priceServices()->where('city_id',$cities[$index ]['city_id'])->first()->price;
        $cost +=  $cities[$index]['price_city'];

        if ($index > 0 && $cities[$index - 1]['city_id'] - $cities[$index]['city_id']) {
            $from = $this->states->where('state_id', $cities[$index - 1]['city_id'])->first(); // from city
            $to = $this->states->where('state_id', $cities[$index]['city_id'])->first();//to city

            $cities[$index]['routing'] = $this->distance((float)$from->lat,(float)$from->longitude,(float)$to->lat,(float)$to->longitude)*$this->country_price->price;
            $cost += $cities[$index]['routing'] ;
        }
        else{
            $cities[$index]['routing']=0;
        }
        return $this->calculateRoutingForCities($cities, $index + 1 , $cost);
    }

    public function returnResponseForCities()
    {
        return ['cities'=>$this->cities, 'cost' => $this->cost];

    }
    public function storeOrderWithDetailsForUser($order, $model)
    {
        $profit =round($this->sub_total * ($this->country_price->fees/100),2);
        $taxAmount =round($this->sub_total * ($this->country_price->tax/100),2);
        $fees = round($this->country_price->fees,2);
        $tax = round($this->country_price->tax,2);
        $cost = round($this->sub_total + $taxAmount + $profit,2);

        return OrderRepository::createOrderWithDetails(array_merge($order,['cost'=>$cost],['sub_total'=>$this->sub_total],['profit'=>$profit],['tax_amount'=>$taxAmount],['fees'=>$fees],['tax'=>$tax]),$this->cities,$model,$this->country_price,$this->sub_total);
    }
}
