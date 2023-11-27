<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\MursheedUser;

class AllMursheedUsersController extends Controller
{
    public function allMursheedUsers()
    {

        $sixMonthsAgo = Carbon::now()->subMonths(6);

        $touristCount = MursheedUser::where('user_type', 'App\Models\Tourist')->where('created_at', '>=', $sixMonthsAgo)->count();
        $driverCount = MursheedUser::where('user_type', 'App\Models\Driver')->where('created_at', '>=', $sixMonthsAgo)->count();
        $guidCount = MursheedUser::where('user_type', 'App\Models\Guides')->where('created_at', '>=', $sixMonthsAgo)->count();

        if ($touristCount === 0 && $driverCount === 0 && $guidCount === 0) {
            return response([
                "message" => "No data found for the last six months",
                "status" => false,
            ], 404);
        }

        return response([
            "data" => [
                "tourist" => $touristCount,
                "driver" => $driverCount,
                "guide" => $guidCount,
            ],
            "message" => "Get Users Mursheed Count Success for last six months",
            "status" => true,
        ], 200);
    }

}
