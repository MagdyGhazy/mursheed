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
        $tickets = Ticket::with(['user.user:id,name,email', 'message', 'replay'])
            ->get()
            ->map(function ($ticket) {
                $messages = $ticket->message->toArray();
                $replies = $ticket->replay->toArray();

                $array1WithTypes = array_map(function ($item) {
                    $item['type'] = 'message';
                    return $item;
                }, $messages);

                $array2WithTypes = array_map(function ($item) {
                    $item['type'] = 'reply';
                    return $item;
                }, $replies);

                $mergedData = array_merge($array1WithTypes, $array2WithTypes);

                usort($mergedData, function($a, $b) {
                    return strcmp($a['created_at'], $b['created_at']);
                });

                $ticketData = [
                    "id" => $ticket->id,
                    "number" => $ticket->number,
                    "title" => $ticket->title,
                    "status" => $ticket->status,
                    "priority" => $ticket->priority,
                    "type" => $ticket->type,
                    "ticket_user_id" => $ticket->user_id,
                    "user" =>$ticket->user->user,
                    "conversation" => $mergedData,
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

        $ticket = Ticket::with(['user.user:id,name,email', 'message', 'replay'])->find($id)
        ->makeHidden(['created_at', 'updated_at', 'user_id']);
        $messages = $ticket->message->toArray();
        $replies = $ticket->replay->toArray();

        $array1WithTypes = array_map(function ($item) {
            $item['type'] = 'message';
            return $item;
        }, $messages);

        $array2WithTypes = array_map(function ($item) {
            $item['type'] = 'reply';
            return $item;
        }, $replies);

            $mergedArray = array_merge($array1WithTypes, $array2WithTypes);


            usort($mergedArray, function ($a, $b) {
                return strtotime($a['created_at']) - strtotime($b['created_at']);
            });

            $ticketData = [
                "id" => $ticket->id,
                "number" => $ticket->number,
                "title" => $ticket->title,
                "status" => $ticket->status,
                "priority" => $ticket->priority,
                "type" => $ticket->type,
                "ticket_user_id" => $ticket->user_id,
                "user" =>$ticket->user->user,
                "conversation" => $mergedArray,
            ];

        return response([
            "status" => "success",
            "ticket" => $ticketData,
        ], 200);

    }

    public function userTickets()
    {
        $UserId = auth()->user()->id;

        $ticket = Ticket::where('user_id',$UserId)->get()
            ->makeHidden(['created_at', 'updated_at'])
            ->map(function ($ticket) {
                $messages = $ticket->message->toArray();
                $replies = $ticket->replay->toArray();

                $array1WithTypes = array_map(function ($item) {
                    $item['type'] = 'message';
                    return $item;
                }, $messages);

                $array2WithTypes = array_map(function ($item) {
                    $item['type'] = 'reply';
                    return $item;
                }, $replies);

                 $mergedArray = array_merge($array1WithTypes, $array2WithTypes);

                usort($mergedArray, function ($a, $b) {
                    return strtotime($a['created_at']) - strtotime($b['created_at']);
                });

                $ticketData = [
                    "id" => $ticket->id,
                    "number" => $ticket->number,
                    "title" => $ticket->title,
                    "status" => $ticket->status,
                    "priority" => $ticket->priority,
                    "type" => $ticket->type,

                    "conversation" => $mergedArray,
                ];
                return $ticketData;
            })
            ->toArray();

        return response([
            "status" => "success",
            'ticket_user_id' => (int)$UserId,
            "tickets" => $ticket,
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
