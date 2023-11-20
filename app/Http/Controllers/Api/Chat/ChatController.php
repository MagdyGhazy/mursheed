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
        $friends = User::where('id', '<>', auth()->guard('api')->user()->id)->get(['id','first_name','email']);
        return response([
            "data" => $friends,
            "message" => "Success",
            "status" => true,
        ], 200);
    }
    public function chats()
    {
        $user = Auth::user();
        $chats = $user->conversations()->with(['lastMessage','participants'=> function($builder) use($user){
            $builder -> where('id', '<>', $user->id)->where('role','admin');
        }])->get();
        return response([
            "data" => $chats,
            "message" => "Success",
            "status" => true,
        ], 200);
    }
}
