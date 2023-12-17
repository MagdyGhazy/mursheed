<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Models\Conversation;


class ConversationController extends Controller
{
    // Get All Conversations
    public function index()
    {
        $conversations = Conversation::with([
            'Message.user:id,user_type,user_id',
            'Message.user.user:id,name,email',
            'Message.user.user.media',
            'Replies.user:id,first_name,email'
        ])->get();

        return response([
            "data" => $conversations,
            "message" => "All Conversations Successfully",
            "status" => true,
        ], 200);
    }


    // Get One Conversation From Id
    public function getOneConversation($id)
    {
        $conversation = Conversation::with([
            'Message.user:id,user_type,user_id',
            'Message.user.user:id,name,email',
            'Replies.user:id,first_name,email'
        ])->find($id);

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
