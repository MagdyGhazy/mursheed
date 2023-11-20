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
        $friends = User::where('id', '<>', auth()->guard('api')->user()->id)->get(['id', 'first_name', 'email']);
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

        $chats = $user->conversations()->with([
            'lastMessage',
            'participants' => function ($builder) use ($user) {
                $builder->where('id', '<>', $user->id)->where('role', 'admin');
            }
        ])->get();

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
