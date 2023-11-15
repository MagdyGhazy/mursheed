<?php

namespace App\Http\Controllers\Api\Chat;

use Throwable;
use App\Models\Recipient;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Events\MessageCreated;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\Conversions\Conversion;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $user = Auth::user();
        $conversation = $user->conversations()->findOrFail($id);
        return $conversation->messages()->paginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string'],
            'conversation_id' => [
                'required_if:user_id,null',
                'integer',
                'exists:conversations,id',
            ],
            'user_id' => [
                'required_if:conversation_id,null',
                'integer',
                'exists:users,id',
            ],
        ]);
        

        $user =  Auth::user();
        $conversation_id = $request->post('conversation_id');
        $user_id = $request->post('user_id');


        DB::beginTransaction();

        try {

            if ($conversation_id) {
                $conversation = $user->conversations()->findOrFail($conversation_id);
            } else {
                $conversation = Conversation::where('type', '=', 'peer')
                    ->whereHas('participants', function ($builder) use ($user_id, $user) {
                        $builder->join('participants as participants2', 'participants2.conversation_id', 'participants.conversation_id')
                            ->where('participants.user_id', '=', $user_id)
                            ->where('participants2.user_id', '=', $user_id);
                    })->first();

                if (!$conversation) {
                    $conversation = Conversation::create([
                        'user_id' => $user->id,
                        'type' => 'peer',
                    ]);
                    $conversation->participants()->attach([
                        $user->id => ['joined_at' => now()],
                        $user_id => ['joined_at' => now()],
                    ]);
                }

            }

            $message = $conversation->messages()->create([
                'user_id' => $user->id,
                'body' => $request->post('message'),
            ]);

            DB::statement('
            INSERT INTO recipients (user_id, message_id) 
            SELECT participants.user_id, ? FROM participants 
            WHERE participants.conversation_id = ?
        ', [$message->id, $conversation->id]);

        $conversation->update([
            'last_message_id'=>$message->id,
        ]);
        

            DB::commit();

            broadcast(new MessageCreated($message));

        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return response([
            "data" => $message ,
            "message" => "Success",
            "status" => true,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Recipient::where([
            'user_id' => Auth::id(),
            'message_id' => $id,
        ])->delete();

        return response([
            "data" => null,
            "message" => "Deleted Success",
            "status" => true,
        ], 200);
    }
}
