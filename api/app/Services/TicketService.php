<?php

namespace App\Services;

use App\Repositories\TicketRepository;
use Illuminate\Database\Eloquent\Collection;

class TicketService
{
    public function __construct(private TicketRepository $ticketRepository) {}

    public function findAll(): Collection
    {
        return $this->ticketRepository->findAll();
    }
}
