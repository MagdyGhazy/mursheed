<?php

namespace App\Http\Controllers\MobileApi;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Guides;
use App\Http\Requests\FavouriteRequest;

class FavouriteController extends Controller
{
    public function index()
    {
        $favourites = Favourite::where('tourist_id',auth()->user()->user_id)->get();
        return response()->json([
            "message" => "Favourite successfully created",
            "data" =>$favourites,

        ]);
    }
    public function store(FavouriteRequest $request)
    {
        $data = $request->except("service_id", "type");
        $data['tourist_id'] = auth()->user()->user_id;


        $service_id = $request->validated("service_id");
        $type = $request->validated("type");
$notify=0;
        $favourites = null;

        if ($type == 0) {
            $flag = Favourite::where('service_id',$service_id)->where('service_type','App\Models\Driver')->where('tourist_id',auth()->user()->user_id)->count();
            if($flag != 0){
                $deiver = Favourite::where('service_id',$service_id)->where('service_type','App\Models\Driver')->where('tourist_id',auth()->user()->user_id)->first();
                $deiver->delete();
                $notify++;
                return response()->json([
                    "message" => "Favourite cancelled",
                    "is_favourite"=> false,
                ]);
            }
            $favourites = Driver::findOrFail($service_id);
        }
        if ($type == 1) {
            $flag = Favourite::where('service_id',$service_id)->where('service_type','App\Models\Guides')->where('tourist_id',auth()->user()->user_id)->count();
            if($flag != 0){
                $Guides = Favourite::where('service_id',$service_id)->where('service_type','App\Models\Guides')->where('tourist_id',auth()->user()->user_id)->first();
                $Guides->delete();
                $notify++;

                return response()->json([
                    "message" => "Favourite cancelled",
                    "is_favourite"=> false,
                ]);
            }
            $favourites = Guides::findOrFail($service_id);
        }
        try {
             $favourites->favourites()->create($data);
//            $favourites->save();

                return response()->json([
                    "message" => "Favourite successfully created",
                    "is_favourite"=> true,
                ]);

            }


         catch (\Throwable $th) {
            return response()->json([
                "message" => "Favourite unsuccessfully created",
                "error" => $th->getMessage(),
            ]);
        }
    }
}
