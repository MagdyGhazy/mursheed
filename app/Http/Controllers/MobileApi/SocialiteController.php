<?php

namespace App\Http\Controllers\MobileApi;

use driver;
use App\Models\MursheedUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect($provider)
    {
        return  Socialite::driver($provider)->redirect();
    }
    public function callback($provider)
    {
      

        $user = Socialite::driver($provider)->user();
        $user_data_token = Socialite::driver($provider)->userFromToken($user->token);
        $userSocialAccount = MursheedUser::where('email', $user->email)->first();
       // $token = $user->createToken("API TOKEN")->accessToken;
        if ($userSocialAccount) {
            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->token,
                'user'  => $userSocialAccount
            ], 200);
        }

        MursheedUser::create(
            [
                "user_type" => "driver",
                "user_id" =>1,
                "email" =>$user->email,
                "password" => 165469
            ]
        );
    }
    public function index()
    {
        return response()->json("jhkjhkj");
    }
}
