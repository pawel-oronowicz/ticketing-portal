<?php

namespace App\Services;

use App\Events\TicketUpdateCreated;
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
     * @param User $user
     * @return Collection
     */
    public function findAllByTicket(Ticket $ticket, User $user): Collection
    {
        if($user->role->isInternal()) {
            return $this->ticketUpdateRepository->findAllByTicket($ticket);
        }

        return $this->ticketUpdateRepository->findAllByTicket($ticket, true);
    }

    /**
     * @param Ticket $ticket
     * @param array $data
     * @param User $user
     * @return TicketUpdate
     */
    public function create(Ticket $ticket, array $data, User $user): TicketUpdate
    {
        $ticketUpdate = $this->ticketUpdateRepository->create($ticket, $data, $user);

        TicketUpdateCreated::dispatch($ticketUpdate);

        return $ticketUpdate;
    }
}
