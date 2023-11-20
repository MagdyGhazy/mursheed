<?php

namespace App\Http\Controllers\Api\Tickets;

use App\Http\Controllers\Api\Services\TicketServices;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\AddMessageRequest;
use App\Http\Requests\Ticket\AddReplayRequest;
use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Models\Tickets\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    protected $ticketServices;
    public function __construct(TicketServices $ticketServices)
    {
        $this->ticketServices = $ticketServices;
    }

    public function index()
    {
        return $this->ticketServices->index();
    }

    public function show($id)
    {
        return $this->ticketServices->show($id);
    }

    public function userTickets($UserId)
    {
        return $this->ticketServices->userTickets($UserId);
    }

    public function store(StoreTicketRequest $request)
    {
        return $this->ticketServices->createTicket($request);
    }

    public function addReplay(AddReplayRequest $request , $ticket_id)
    {
        return $this->ticketServices->createReplay($request,$ticket_id);
    }

    public function addMessage(AddMessageRequest $request,$ticket_id)
    {
        return $this->ticketServices->createMessage($request['message'],$ticket_id);
    }

    public function inActiveTicket($ticket_id)
    {
        return $this->ticketServices->inActiveTicket($ticket_id);
    }
}
