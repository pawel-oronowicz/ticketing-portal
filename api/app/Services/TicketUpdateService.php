<?php

namespace App\Services;

use App\Models\Ticket;
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
}
