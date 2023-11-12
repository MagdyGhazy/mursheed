<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $setting = Settings::query()->first();

        return response()->json([
            "setting" => $setting,

        ], 200);
    }
    public function store(Request $request)
    {



        $request->validate([
            'phone_number' => ["string", "required"],
            'email' => ["string", "required"],
            'currency' => ["string", "required"],
            'social_links' => ["required", "string"],
        ]);

        $setting = Settings::create([
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'currency' => $request->currency,
            'social_links' => $request->social_links,
        ]);

        return response()->json([
            "setting" => $setting,

        ], 201);
    }
    public function update(Request $request, Settings $setting)
    {
        $request->validate([
            'phone_number' => ["string", "required"],
            'email' => ["string", "required"],
            'currency' => ["string", "required"],
            'social_links' => ["required", "string"],
        ]);

        $setting->update([
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'currency' => $request->currency,
            'social_links' => $request->social_links,
        ]);

        return response()->json([
            "setting" => $setting,

        ], 202);
    }
}
