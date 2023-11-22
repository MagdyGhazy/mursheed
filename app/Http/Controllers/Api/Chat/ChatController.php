<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Models\MursheedUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        
        $friends = MursheedUser::select('id', 'user_type', 'email')->get();
        if ($friends == null) {
            return response([
                "data" => null,
                "message" => "Friends Not Found",
                "status" => false,
            ], 404);
        } else {
            return response([
                "data" => $friends,
                "message" => "Success",
                "status" => true,
            ], 200);
        }
    }
    public function chats()
    {
        $user = Auth::user();

        if (!$user) {
            return response([
                "data" => null,
                "message" => "User Not Authenticated",
                "status" => false,
            ], 401);
        }

        $chats = $user->conversations()->with(['lastMessage', 'participants'])->get();


        if ($chats == null) {
            return response([
                "data" => null,
                "message" => "Chats Not Found",
                "status" => false,
            ], 404);
        }

        return response([
            "data" => $chats,
            "message" => "Success",
            "status" => true,
        ], 200);
    }

}
