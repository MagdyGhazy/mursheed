<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MursheedUser;
use App\Models\OTP;
use App\Notifications\SendOtpNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OTPController extends Controller
{
    public function generateOTP($identifier = null, $type = null)
    {

        if ($identifier == null || $type == null) {
            $identifier = request()->identifier;
            $type = request()->type;
        }

        $user = MursheedUser::where('email', $identifier)->first();

        if (!$user) {
            return response()->json([
                'message' => 'no user attached to that email address'
            ], 422);
        }

        OTP::query()
            ->where('identifier', $user->email)
            ->where('valid', true)
            ->where('type', $type)
            ->delete();

        $otp = rand(1111, 9999);

        OTP::create([
            'identifier' => $user->email,
            'otp' => $otp,
            'type' => $type,
            'expire_at' => Carbon::now()->addMinutes(10)
        ]);

        $user->notify(new SendOtpNotification($otp));

        return response()->json([
            'message' => 'otp created successfully'
        ], 201);
    }

    public function validateOTP(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string', 'email'],
            'type' => ['required', 'in:0,1,2'],
            'otp' => ['required', 'string']
        ]);

        $user = MursheedUser::where('email', $request->identifier)->first();

        $otp = OTP::query()
            ->where('identifier', $request->identifier)
            ->where('otp', $request->otp)
            ->where('type', $request->type)
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


        if ($request->type == 0) {
            $user->email_verified_at = Carbon::now();
            $user->save();
            $otp->valid = false;
            $otp->save();
        }

        if ($request->type == 2) {
            $otp->valid = true;
            $otp->expire_at = Carbon::now()->addMinutes(30);
            $otp->save();
        }

        return response()->json([
            'message' => 'otp validated successfully',
        ], 202);
    }
}
