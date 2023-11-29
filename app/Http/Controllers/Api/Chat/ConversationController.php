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
        $conversations = Conversation::with('Message.mursheedUsers:user_type', 'Replies.user:email')->get();
    
        return response([
            "data" => $conversations,
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
    
        $messages = $conversation->Message->toArray();
        $replies = $conversation->Replies->toArray();
    
        foreach ($messages as &$message) {
            $message['table_name'] = 'messages';
        }
    
        foreach ($replies as &$reply) {
            $reply['table_name'] = 'replies';
        }
    
        $mergedData = array_merge($messages, $replies);
    
        usort($mergedData, function($a, $b) {
            return strcmp($a['created_at'], $b['created_at']);
        });
    
        return response([
            "data" => $mergedData,
            "message" => "Get One Conversation Successfully",
            "status" => true,
        ], 200);
    }
}
