<?php

namespace App\Http\Controllers\Api\Services;

use App\Enums\TicketStatusEnum;
use App\Models\MursheedUser;
use App\Models\Tickets\Message;
use App\Models\Tickets\MessageReplay;
use App\Models\Tickets\Replay;
use App\Models\Tickets\Ticket;
use App\Models\Tickets\TicketMessage;
use App\Models\Tickets\TicketReplay;
use App\Models\User;
use Illuminate\Support\Str;

class TicketServices
{

    public function index()
    {
        $tickets = Ticket::with(['user.user', 'message', 'replay'])
            ->get()
            ->makeHidden(['created_at', 'updated_at', 'user_id'])
            ->map(function ($ticket) {
                $user = $ticket->user->user;
                $ticketData = [
                    "id" => $ticket->id,
                    "number" => $ticket->number,
                    "title" => $ticket->title,
                    "status" => $ticket->status,
                    "priority" => $ticket->priority,
                    "type" => $ticket->type,
                    "user" =>$user,
                    "message" => $ticket->message,
                    "replay" => $ticket->replay,
                ];
                return $ticketData;
            })
            ->toArray();

        return response([
            "status" => "success",
            "tickets" => $tickets,
        ], 200);
    }
    public function show($id)
    {

        $ticket = Ticket::with(['user.user', 'message', 'replay'])->find($id);
        $user = User::where('id',auth()->user()->id)->first();

        $response = [
            "status" => "success",
            "user" => $user,
            "tickets" => [
                [
                    "id" => $ticket->id,
                    "number" => $ticket->number,
                    "title" => $ticket->title,
                    "status" => $ticket->status,
                    "priority" => $ticket->priority,
                    "type" => $ticket->type,
                    "user" => $ticket->user->user,
                    "message" => $ticket->message,
                    "replay" => $ticket->replay,
                ],
            ],
        ];
        return response($response, 200);
    }

    public function userTickets($UserId)
    {
        $ticket = Ticket::with(['user'])->where('user_id',$UserId)->get()
            ->makeHidden(['created_at', 'updated_at'])
            ->map(function ($ticket) {
                $user = $ticket->user->user;
                $ticketData = [
                    "id" => $ticket->id,
                    "number" => $ticket->number,
                    "title" => $ticket->title,
                    "status" => $ticket->status,
                    "priority" => $ticket->priority,
                    "type" => $ticket->type,
                    "user" =>$user,
                    "message" => $ticket->message,
                    "replay" => $ticket->replay,
                ];
                return $ticketData;
            })
            ->toArray();

        return response([
            "status" => "success",
            "ticket" => $ticket,
        ], 200);
    }


    public function createMessage($message ,$ticket_id)
    {
        return TicketMessage::create([
            'content' => $message,
            'ticket_id' => $ticket_id,
        ]);
    }

    public function createReplay($request,$ticket_id)
    {
        $ticket = Ticket::find($ticket_id);
        $ticket->update(['status' => TicketStatusEnum::ACTIVE]);

        return TicketReplay::create([
            'content' => $request['replay'],
            'ticket_id' => $ticket_id,
        ]);
    }



    public function createTicket($request)
    {
        $data = $request->all();

        $data['user_id'] = auth()->id();
        $data['number'] = 'MUR|'. Str::random(10) . '|' . $data['user_id'] ;
        $data['status'] = TicketStatusEnum::PENDING ;

        $ticket = Ticket::create($data);


        $this->createMessage($data['message'],$ticket['id']);


        return response([
            "status" => "success",
            "ticket" => $ticket,
        ], 200);
    }

    public function inActiveTicket($ticket_id)
    {
        $ticket = Ticket::find($ticket_id);
        $ticket->update(['status' => TicketStatusEnum::INACTIVE]);

        return response([
            "status" => "success",
            "ticket" => $ticket,
        ], 200);
    }
}