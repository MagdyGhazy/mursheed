<?php

namespace App\Http\Controllers\Api\Chat;

use Throwable;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
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
    public function createConversation(Request $request)
    {

        DB::beginTransaction();
        try {
            $conversation = Conversation::create();

            $message = Message::create(
                [
                    'content' => $request['content'],
                    'user_id' => auth()->user()->id,
                    'conversation_id' => $conversation->id,
                ]
            );

            DB::commit();
            broadcast(new MessageCreated($message));

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
