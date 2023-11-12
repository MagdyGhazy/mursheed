<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Language;
use App\Models\State;
use Illuminate\Http\Request;

class CountryStateController extends Controller
{
    public function getStateInCountry(Country $country)
    {
        return response(['states'=> State::where('country_id',$country->id)->where('lang','en')->get(['state','state_id'])]);
    }


    public function getCountry( )
    {
        return response(['countries'=>Country::where('lang','en')->get(['country_id','country'])]);
    }

    public function langauages()
    {
        return response(['languages'=>Language::all()]);
    }
}
