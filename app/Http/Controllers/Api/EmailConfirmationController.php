<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MursheedUser;
use App\Models\OTP;
use App\Notifications\SendOtpNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmailConfirmationController extends Controller
{

    public function registerOTP($user)
    {
        OTP::query()
            ->where('identifier', $user->email)
            ->where('valid', true)
            ->delete();

        $otp = rand(1111, 9999);

        OTP::create([
            'identifier' => $user->email,
            'otp' => $otp,
            'expire_at' => Carbon::now()->addMinutes(10)
        ]);

        $user->notify(new SendOtpNotification($otp));

        return response()->json([
            'message' => 'otp created successfully'
        ], 201);
    }

    public function generateEmailOTP(MursheedUser $user)
    {

        if (!$user->email) {
            $user = request()->user();
        }

        OTP::query()
            ->where('identifier', $user->email)
            ->where('valid', true)
            ->delete();

        $otp = rand(1111, 9999);

        OTP::create([
            'identifier' => $user->email,
            'otp' => $otp,
            'expire_at' => Carbon::now()->addMinutes(10)
        ]);

        $user->notify(new SendOtpNotification($otp));

        return response()->json([
            'message' => 'otp created successfully'
        ], 201);
    }

    public function validateEmailOTP(Request $request)
    {

        $request->validate([
            "otp" => ["required", "string"],
        ]);

        $otp = OTP::where('identifier', $request->user()->email)->where('otp', $request->otp)->first();

        if ($otp == null) {
            return response()->json(['message' => 'otp does not exist'], 404);
        }

        if (!$otp->valid) {
            return response()->json(['message' => 'otp is not valid'], 403);
        }

        $now = Carbon::now();
        $validity = $otp->expire_at;

        if (strtotime($validity) < strtotime($now)) {
            $otp->delete();
            return response()->json(['message' => 'otp expired'], 419);
        }

        $otp->valid = false;
        $otp->save();


        $request->user()->email_verified_at = Carbon::now();
        $request->user()->save();

        return response()->json([
            'message' => 'otp validated successfully',
            'user' => $request->user()
        ], 202);
    }
}
