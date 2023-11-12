<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MursheedUser;
use App\Models\PasswordOTP;
use App\Notifications\SendPasswordRessetToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordRessetController extends Controller
{

    public function generatePasswordOTP(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $user = MursheedUser::where('email', $request->email)->first();

        if ($user == null) {
            return response()->json([
                'message' => 'user not found',
            ]);
        }

        PasswordOTP::where('email', $user->email)->delete();

        $otp = rand(1111, 9999);

        PasswordOTP::create([
            'email' => $user->email,
            'otp' => $otp,
            'expire_at' => Carbon::now()->addMinutes(30),
            'valid' => true,
        ]);

        $user->notify(new SendPasswordRessetToken($otp));

        return response()->json([
            "message" => 'a reset token has been sent to your email'
        ], 201);
    }

    public function validatePasswordOTP(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'otp' => ['required', 'string']
        ]);

        $user = MursheedUser::where('email', $request->email)->first();
        $otp = PasswordOTP::query()
            ->where('email', $request->email)
            ->where('otp', $request->otp)
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

        $otp->valid = true;
        $otp->expire_at = Carbon::now()->addMinutes(30);
        $otp->save();

        return response()->json([
            'message' => 'otp validated successfully',
        ], 202);
    }

    public function ressetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed'],
            'otp' => ['required', 'string']
        ]);

        $user = MursheedUser::where('email', $request->email)->first();
        $otp = PasswordOTP::query()
            ->where('email', $request->email)
            ->where('otp', $request->otp)
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
