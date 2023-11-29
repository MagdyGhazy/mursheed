<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\OTP;
use App\Models\User;
use App\Models\Driver;
use App\Models\Guides;
use App\Models\Tourist;
use App\Models\MursheedUser;
use Illuminate\Http\Request;
use App\Models\Languagesable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required',
                    'role' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 402);
            }

            $user = User::where('email', $request->email)->first();



            if (!Auth::guard('api')->attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }



            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                "user" =>  $user,
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }



    public function loginClients(Request $request)
    {

        try {

            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            $user = MursheedUser::where('email', $request->email)->first();


            // if ($user->email_verified_at == null)
            // {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'your email must be verified first !',
            //     ], 402);
            // }
            $languages = Languagesable::where('languagesable_id', $user->id)->with([
                'language' => function ($query) {
                    $query->select('id', 'lang');
                }
            ])->get();
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 422);
            }

            if (explode("\\", get_class($user->user))[2] == "Driver") {

                if (Hash::check($request->password, $user->password)) {

                    $driver = Driver::where('email', $request->email)->first();
//                    if (count($driver->getMedia('car_photos')) >= 0) {
                         $car_photos = [] ;
                        foreach ($driver->getMedia('car_photos') as $media) {
                            $car_photos[] = $media->getUrl();
                        }
//                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'User Logged In Successfully',
                        'token' => $user->createToken("API TOKEN")->plainTextToken,
                        "user" => [
                            "id" => $user->user->id,
                            "notification_id" => $user->id,
                            "name" => $user->user->name,
                            "phone" => $user->user->phone,
                            "email" => $user->user->email,
                            "is_verified" => $user->email_verified_at ? true : false,
                            "type" =>  explode("\\", get_class($user->user))[2],
                            "nationality" => $user->user->nationality,
                            "country_id" => $user->user->country_id,
                            "state_id" => $user->user->state_id,
                            "gender" =>  $user->user->gender ? ($user->user->gender == 1 ? "male" : "female") : null,
                            "des_city_id" => $user->user->dest_city_id,
                            "bio" => $user->user->bio,
                            "car_number" => $user->user->car_number,
                            "driver_licence_number" => $user->user->driver_licence_number,
                            "gov_id" => $user->user->gov_id,
                            "status" => $user->user->status,
                            "car_type" => $user->user->car_type,
                            "car_brand_name" => $user->user->car_brand_name,
                            "car_manufacturing_date" => $user->user->car_manufacturing_date,
                            "admin_rating" => $user->user->admin_rating,
                            "ratings_count" => $user->user->ratings_count,
                            "ratings_sum" => $user->user->ratings_sum,
                            "total_rating" => $user->user->total_rating,
                            "car_photo" => empty($user->user->getMedia('car_photos')) ? url("default_user.jpg") : $car_photos,
                            "personal_photo" => empty($user->user->getFirstMediaUrl('personal_pictures')) ? url("default_user.jpg") : $user->user->getFirstMediaUrl('personal_pictures'),
                            "languages" => $languages
                        ],
                    ], 200);
                }
            }

            if (explode("\\", get_class($user->user))[2] == "Guides") {
                if (Hash::check($request->password, $user->password)) {
                    return response()->json([
                        'status' => true,
                        'message' => 'User Logged In Successfully',
                        'token' => $user->createToken("API TOKEN")->plainTextToken,
                        "user" => [
                            "id" => $user->user->id,
                            "notification_id" => $user->id,
                            "name" => $user->user->name,
                            "phone" => $user->user->phone,
                            "email" => $user->user->email,
                            "is_verified" => $user->email_verified_at ? true : false,
                            "type" =>  explode("\\", get_class($user->user))[2],
                            "nationality" => $user->user->nationality,
                            "country_id" => $user->user->country_id,
                            "state_id" => $user->user->state_id,
                            "gender" =>  $user->user->gender ? ($user->user->gender == 1 ? "male" : "female") : null,
                            "des_city_id" => $user->user->dest_city_id,
                            "bio" => $user->user->bio,
                            "car_number" => $user->user->car_number,
                            "status" => $user->user->status,
                            "admin_rating" => $user->user->admin_rating,
                            "ratings_count" => $user->user->ratings_count,
                            "ratings_sum" => $user->user->ratings_sum,
                            "total_rating" => $user->user->total_rating,
                            "languages" => $languages,
                            "personal_photo" => empty($user->user->getFirstMediaUrl('personal_pictures')) ? url("default_user.jpg") : $user->user->getFirstMediaUrl('personal_pictures'),
                        ],
                    ], 200);
                }
            }

            if (explode("\\", get_class($user->user))[2] == "Tourist") {
                if (Hash::check($request->password, $user->password)) {
                    return response()->json([
                        'status' => true,
                        'message' => 'User Logged In Successfully',
                        'token' => $user->createToken("API TOKEN")->plainTextToken,
                        "user" => [
                            "id" => $user->user->id,
                            "notification_id" => $user->id,
                            "name" => $user->user->name,
                            "phone" => $user->user->phone,
                            "email" => $user->user->email,
                            "is_verified" => $user->email_verified_at ? true : false,
                            "type" =>  explode("\\", get_class($user->user))[2],
                            "nationality" => $user->user->nationality,
                            "country_id" => $user->user->country_id,
                            "state_id" => $user->user->state_id,
                            "languages" => $languages,
                            "gender" =>  $user->user->gender ? ($user->user->gender == 1 ? "male" : "female") : null,
                            "des_city_id" => $user->user->dest_city_id,
                            "personal_photo" => empty($user->user->getFirstMediaUrl('personal_pictures')) ? url("default_user.jpg") : $user->user->getFirstMediaUrl('personal_pictures'),
                        ],
                    ], 200);
                }
            }

            if ($user != null) {
                if (Hash::check($request->password, $user->password)) {
                    return response()->json([
                        'status' => true,
                        'message' => 'User Logged In Successfully',
                        'token' => $user->createToken("API TOKEN")->plainTextToken,
                        "user" => [
                            "id" => $user->user->id,
                            "notification_id" => $user->id,
                            "name" => $user->user->name,
                            "phone" => $user->user->phone,
                            "email" => $user->user->email,
                            "languages" => $languages,
                            "is_verified" => $user->email_verified_at ? true : false,
                            "type" =>  explode("\\", get_class($user->user))[2],
                            "nationality" => $user->user->nationality,
                            "country_id" => $user->user->country_id,
                            "state_id" => $user->user->state_id,
                            "gender" =>  $user->user->gender ? ($user->user->gender == 1 ? "male" : "female") : null,
                            "des_city_id" => $user->user->dest_city_id,
                            "personal_photo" => empty($user->user->getFirstMediaUrl('personal_pictures')) ? url("default_user.jpg") : $user->user->getFirstMediaUrl('personal_pictures'),
                        ],
                    ], 200);
                } else {
                    return response()->json(['message' => 'email or password does not match our records'], 401);
                }
            } else {
                return response()->json(['message' => 'email or password does not match our records'], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }



    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response(["status" => true, "message" => "success"]);
    }

    public function logoutt()
    {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function logoutAll()
    {
        Session::regenerate();
        return response()->json(['message' => 'all Successfully logged out']);
    }

    public function ressetPassword(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed'],
            'otp' => ['required', 'string']
        ]);

        $user = MursheedUser::where('email', $request->identifier)->first();

        $otp = OTP::query()
            ->where('identifier', $request->identifier)
            ->where('otp', $request->otp)
            ->where('type', '2')
            ->where('valid', true)
            ->first();

        if ($user == null) {
            return response()->json([
                'message' => 'user not found',
            ], 404);
        }

        if ($otp == null) {
            return response()->json([
                'message' => 'otp not found',
            ], 404);
        }

        if ($otp->valid != true) {
            return response()->json([
                'message' => 'otp not found',
            ], 404);
        }

        $now = Carbon::now();

        if (strtotime($otp->expire_at) < strtotime($now)) {
            $otp->delete();
            return response()->json([
                'message' => 'otp is not valid anymore request a new otp',
            ], 403);
        }

        $sub_user = $user->user;

        $user->update([
            "password" => Hash::make($request->password)
        ]);
        $sub_user->update([
            "password" => Hash::make($request->password)
        ]);

        $otp->delete();

        return response()->json([
            'message' => 'your password was successfully reset, login now with your new credentials',
        ], 202);
    }
}
