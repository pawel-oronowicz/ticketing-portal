<?php

namespace App\Services;

use App\Models\Ticket;
use App\Repositories\TicketRepository;
use Illuminate\Database\Eloquent\Collection;

class TicketService
{
    public function __construct(private TicketRepository $ticketRepository) {}

    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return $this->ticketRepository->findAll();
    }

    /**
     * @param int $id
     * @return Ticket|null
     */
    public function findById(int $id): ?Ticket
    {
        return $this->ticketRepository->findById($id);
    }
}
