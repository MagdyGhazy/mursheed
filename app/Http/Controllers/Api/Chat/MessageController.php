<?php

namespace App\Http\Controllers\Api\Chat;

use Throwable;
use App\Models\Replay;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Events\ReplayCreated;
use App\Events\MessageCreated;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function index()
    {
        $Message = Message::with('mursheedUsers:user_type')->get();
        return response([
            "data" => $Message,
            "message" => "All Messages Successfully",
            "status" => true,
        ], 200);
    }



    //Get Messages Conversation From Conversation ID
    public function getOneMessage($id)
    {
        $messages = Message::where('conversation_id', $id)->get();
        if (!$messages) {
            return response([
                "message" => "Messages not found",
                "status" => false,
            ], 404);
        }
        return response([
            "data" => $messages,
            "message" => "Get One Messages Successfully",
            "status" => true,
        ], 200);
    }
    
    // Create Conversation And Send Message In this Conversation From ID
    public function createConversation(Request $request)
    {

        DB::beginTransaction();
        try {
            $conversation = Conversation::create();

            // Auth replay user
            $userId = auth()->user()->id;

            $message = Message::create([
                'content' => $request['content'],
                'user_id' => auth()->user()->id,
                'conversation_id' => $conversation->id,
            ]);

            DB::commit();
            broadcast(new MessageCreated($message));

            //Call the automatic response function
            $this->createAutomaticReply($conversation,$userId);

            return response([
                "data" => $message,
                "message" => "Create Conversation And Send Message Successfully",
                "status" => true,
            ], 200);

        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    //Sending an automatic response when the user sends the first message
    private function createAutomaticReply($conversation, $userId)
    {
        $automaticReplyContent = "مرحبا سيتم الرد علي رسالتك قريبا";
        $automaticReply=Replay::create([
            'content' => $automaticReplyContent,
            'user_id' => $userId,
            'conversation_id' => $conversation->id,
        ]);
        broadcast(new ReplayCreated($automaticReply));
    }


    //Sending a message based on the conversation ID
    public function createMessage(Request $request)
    {

        DB::beginTransaction();
        try{
            $conversation_id = $request->input('conversation_id');
            $message = Message::create(
                [
                    'content' => $request['content'],
                    'user_id' => auth()->user()->id,
                    'conversation_id' => $conversation_id,
                ]
            );
    
            DB::commit();
            broadcast(new MessageCreated($message));
    
            return response([
                "data" => $message,
                "message" => "Send Message Successfully",
                "status" => true,
            ], 200);

        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
