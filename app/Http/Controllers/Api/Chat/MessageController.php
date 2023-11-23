<?php

namespace App\Http\Controllers\Api\Chat;

use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function index()
    {
        $Message = Message::all(); 
        return response([
            "data" => $Message,
            "status" => true,
        ], 200);
    }
    public function createConversation(Request $request)
    {

        $con = Conversation::create();

        $message = Message::create(
            [
                'content' => $request['content'],
                'user_id' => $request['user_id'],
                'conversation_id' => $con->id,
            ]
        );
        return response([
            "data" => $message,
            "status" => true,
        ], 200);
    }


    public function createMessage(Request $request)
    {

        $conversation_id = $request->input('conversation_id');
        $message = Message::create(
            [
                'content' => $request['content'],
                'user_id' => $request['user_id'],
                'conversation_id' => $conversation_id,
            ]
        );
        return response([
            "message" => $message,
            "status" => true,
        ], 200);
    }
}
