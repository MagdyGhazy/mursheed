<?php

namespace App\Http\Controllers\MobileApi;

use driver;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect($provider)
    {
       return  Socialite::driver($provider)->redirect();
    }
    public function callback ($provider)
    {
        $user = Socialite::driver($provider)->user();
        return response()->json($user);

    }
}