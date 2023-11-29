<?php

namespace App\Http\Controllers\Api\Chat;

use Throwable;
use App\Models\Replay;
use Illuminate\Http\Request;
use App\Events\ReplayCreated;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReplayController extends Controller
{
    //Get All Replays
    public function index(){
        $Replies = Replay::with('user:email')->get(); 
        return response([
            "data" => $Replies,
            "message" => "All Replays Successfully",
            "status" => true,
        ], 200);
    }


    //Get Replays Conversation From Conversation ID
    public function getOneReplay($id)
    {
        $replay = Replay::where('conversation_id', $id)->get();
        if (!$replay) {
            return response([
                "message" => "Replay not found",
                "status" => false,
            ], 404);
        }
        return response([
            "data" => $replay,
            "message" => "Get One Replay Successfully",
            "status" => true,
        ], 200);
    }


    //Sending a Replays based on the conversation ID
    public function createReplay(Request $request)
    {

        DB::beginTransaction();
        try{
            $conversation_id = $request->input('conversation_id');
            $message = Replay::create(
                [
                    'content' => $request['content'],
                    'user_id' => auth()->user()->id,
                    'conversation_id' => $conversation_id,
                ]
            );
    
            DB::commit();
            broadcast(new ReplayCreated($message));
    
            return response([
                "data" => $message,
                "message" => " Replay Successfully",
                "status" => true,
            ], 200);

        }catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
