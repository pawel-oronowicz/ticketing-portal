<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketUpdate;
use App\Models\User;
use App\Repositories\TicketUpdateRepository;
use Illuminate\Support\Collection;

class TicketUpdateService
{
    public function __construct(private TicketUpdateRepository $ticketUpdateRepository) {}

    /**
     * @param Ticket $ticket
     * @return Collection
     */
    public function findAllByTicket(Ticket $ticket): Collection
    {
        return $this->ticketUpdateRepository->findAllByTicket($ticket);
    }

    /**
     * @param Ticket $ticket
     * @param array $data
     * @param User $user
     * @return TicketUpdate
     */
    public function createTicketUpdate(Ticket $ticket, array $data, User $user): TicketUpdate
    {
        return $this->ticketUpdateRepository->create($ticket, $data, $user);
    }
}
