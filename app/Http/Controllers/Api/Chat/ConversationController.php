<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Conversions\Conversion;

class ConversationController extends Controller
{
    public function index()
    {
        $Conversations = Conversation::with('Message.mursheedUsers:user_type', 'Replies.user:email')->get();
        return response([
            "data" => $Conversations,
            "message" => "All Conversations Successfully",
            "status" => true,
        ], 200);
    }

    public function getOneConversation($id)
    {
        $conversation = Conversation::with('Message.mursheedUsers:user_type', 'Replies.user:email')->find($id);
        if (!$conversation) {
            return response([
                "message" => "Conversation not found",
                "status" => false,
            ], 404);
        }
        return response([
            "data" => $conversation,
            "message" => "Get One Conversation Successfully",
            "status" => true,
        ], 200);
    }
    
}
