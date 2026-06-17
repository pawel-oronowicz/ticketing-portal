<?php

namespace App\Repositories;

use App\Models\Ticket;
use App\Models\TicketUpdate;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TicketUpdateRepository
{
    /**
     * @param Ticket $ticket
     * @return Collection
     */
    public function findAllByTicket(Ticket $ticket): Collection
    {
        return TicketUpdate::with(['createdBy'])
            ->where('ticket_id', $ticket->id)->get();
    }

    /**
     * @param Ticket $ticket
     * @param array $data
     * @param User $user
     * @return TicketUpdate
     */
    public function create(Ticket $ticket, array $data, User $user): TicketUpdate
    {
        $ticketUpdate = new TicketUpdate();
        $ticketUpdate->fill($data);
        $ticketUpdate->ticket_id = $ticket->id;
        $ticketUpdate->created_by_user_id = $user->id;
        $ticketUpdate->save();

        return $ticketUpdate;
    }
}
