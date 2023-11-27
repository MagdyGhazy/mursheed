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
    public function index(){
        $Replies = Replay::all(); 
        return response([
            "data" => $Replies,
            "message" => "All Replays Successfully",
            "status" => true,
        ], 200);
    }
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