<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index()
    {
        $Conversations = Conversation::with('Message','Replies')->get(); 
        return response([
            "data" => $Conversations,
            "message" => "All Conversations Successfully",
            "status" => true,
        ], 200);
    }
}
