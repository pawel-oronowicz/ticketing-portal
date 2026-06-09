<?php

namespace App\Repositories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

class TicketRepository
{
    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Ticket::with(['createdBy', 'assigned', 'company', 'site'])->get();
    }

    /**
     * @param int $id
     * @return Ticket
     */
    public function findById(int $id): Ticket
    {
        return Ticket::with(['createdBy', 'assigned', 'company', 'site'])->find($id);
    }
}
