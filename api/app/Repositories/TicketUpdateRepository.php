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
     * @param bool|null $excludeInternal
     * @return Collection
     */
    public function findAllByTicket(Ticket $ticket, ?bool $excludeInternal = false): Collection
    {
        $ticketUpdates = TicketUpdate::with(['createdBy'])
            ->where('ticket_id', $ticket->id);

        if ($excludeInternal) {
            $ticketUpdates = $ticketUpdates->where('is_internal', false);
        }

        $ticketUpdates = $ticketUpdates->orderBy('created_at')->get();

        return $ticketUpdates;
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
