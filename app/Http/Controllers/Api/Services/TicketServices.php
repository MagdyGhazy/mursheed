<?php

namespace App\Http\Controllers\Api\Services;

use App\Models\Tickets\Message;
use App\Models\Tickets\MessageReplay;
use App\Models\Tickets\Replay;
use App\Models\Tickets\Ticket;
use App\Models\Tickets\TicketMessage;
use App\Models\Tickets\TicketReplay;
use Illuminate\Support\Str;

class TicketServices
{

    public function index()
    {
        $tickets = Ticket::with(['message','replay'])->get()->makeHidden(['created_at', 'updated_at']);

        return response([
            "status" => "success",
            "tickets" => $tickets,
        ], 200);
    }

    public function show($id)
    {

        $ticket = Ticket::with(['message','replay'])->find($id)->makeHidden(['created_at', 'updated_at']);

        return response([
            "status" => "success",
            "ticket" => $ticket,
        ], 200);
    }

    public function userTickets($UserId)
    {
        $ticket = Ticket::with(['user'])->where('user_id',$UserId)->get()->makeHidden(['created_at', 'updated_at']);

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
        $ticket->update(['status' => 'active']);

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
        $ticket->update(['status' => 'inactive']);

        return response([
            "status" => "success",
            "ticket" => $ticket,
        ], 200);
    }
}
