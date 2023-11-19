<?php

namespace App\Http\Controllers\Api\Tickets;

use App\Http\Controllers\Api\Services\TicketServices;
use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        return $this->ticketServices->createTicket($request);
    }

    public function addReplay(Request $request , $ticket_id)
    {
        return $this->ticketServices->createReplay($request,$ticket_id);
    }

    public function addMessage(Request $request,$ticket_id)
    {
        return $this->ticketServices->createMessage($request,$ticket_id);
    }

    public function inActiveTicket($ticket_id)
    {
        return $this->ticketServices->inActiveTicket($ticket_id);
    }
}
