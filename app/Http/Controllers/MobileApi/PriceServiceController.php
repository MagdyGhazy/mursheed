<?php

namespace App\Http\Controllers\MobileApi;

use App\Http\Controllers\Controller;
use App\Models\priceService;
use Illuminate\Http\Request;

class PriceServiceController extends Controller
{
    public function index(Request $request)
    {
        $userPrices = PriceService::where('user_id', $request->user()->user_id)->get();

        return response()->json(["priceServices" => $userPrices]);
    }

    public function show($id, Request $request)
    {
        $priceService = PriceService::where("user_id", $request->user()->user_id)->findOrFail($id);

        return response()->json(["priceService" => $priceService]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'city_id' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $user = $request->user()->user;

        $price = $user->priceServices()->updateOrCreate(
            [
                'city_id' => $request->input('city_id'),
                'user_id' => $user->id,
            ],
            [
                'price' => $request->input('price'),
            ]
        );

        return response()->json(["priceService" => $price], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'city_id' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $priceService = PriceService::findOrFail($id);

        if ($priceService->user_id !== $request->user()->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $priceService->city_id = $request->input('city_id');
        $priceService->price = $request->input('price');

        $priceService->save();

        return response()->json([
            'message' => 'Price service updated successfully',
            "priceService" => $priceService
        ]);
    }

    public function destroy($id, Request $request)
    {
        $priceService = PriceService::where("user_id", $request->user()->user_id)->findOrFail($id);

        $priceService->delete();

        return response()->json(['message' => 'Price service deleted successfully']);
    }
}
