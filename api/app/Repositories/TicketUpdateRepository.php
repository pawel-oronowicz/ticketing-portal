<?php

namespace App\Repositories;

use App\Models\Ticket;
use App\Models\TicketUpdate;
use Illuminate\Database\Eloquent\Collection;

class TicketUpdateRepository
{
    /**
     * @param Ticket $ticket
     * @return Collection
     */
    public function findAllByTicket(Ticket $ticket): Collection
    {
        return TicketUpdate::where('ticket_id', $ticket->id)->get();
    }
}
