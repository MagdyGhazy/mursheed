<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Models\Replay;
use Illuminate\Http\Request;

class ReplayController extends Controller
{
    public function index(){
        $Replies = Replay::all(); 
        return response([
            "data" => $Replies,
            "status" => true,
        ], 200);
    }
    public function createReplay(Request $request)
    {

        $conversation_id = $request->input('conversation_id');
        $message = Replay::create(
            [
                'content' => $request['content'],
                'user_id' => auth()->user()->id,
                'conversation_id' => $conversation_id,
            ]
        );
        return response([
            "data" => $message,
            "status" => true,
        ], 200);
    }
}
