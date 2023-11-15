<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $friends = User::where('id', '<>', auth()->guard('api')->user()->id)->get(['first_name']);
        return response([
            "data" => $friends,
            "message" => "Success",
            "status" => true,
        ], 200);
    }
    public function chats()
    {
        $user = Auth::user();
        $chats = $user->conversations;
        return response([
            "data" => $chats,
            "message" => "Success",
            "status" => true,
        ], 200);
    }
}
