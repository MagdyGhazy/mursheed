<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHandler;
use App\Http\Requests\StoreCountryPrice;
use App\Models\CountryPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CountryPriceController extends Controller
{
    private $ControllerHandler;

    public function __construct()
    {
        $this->ControllerHandler = new ControllerHandler(new CountryPrice());
    }

    public function index()
    {
        return $this->ControllerHandler->getAll("prices");
    }

    public function show(CountryPrice $country_price)
    {
        return $this->ControllerHandler->show("user", $country_price);
    }

    public function store(StoreCountryPrice $request)
    {
        return $this->ControllerHandler->store("price",  $request->validated());
    }

    public function update(StoreCountryPrice $request, CountryPrice $country_price)
    {
        return $this->ControllerHandler->update("user", $country_price, $request->validated());
    }

    public function active(CountryPrice $country_price)
    {
        $country_price->status = true;

        $country_price->save();

        return response()->json([
            "message" => "Country price updated successfully",
            "country_price" => $country_price
        ]);
    }

    public function deActive(CountryPrice $country_price)
    {
        $country_price->status = false;

        $country_price->save();

        return response()->json([
            "message" => "Country price updated successfully",
            "country_price" => $country_price
        ]);
    }
}
