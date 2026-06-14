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
     * @return Ticket|null
     */
    public function findById(int $id): ?Ticket
    {
        return Ticket::with(['createdBy', 'assigned', 'company', 'site'])->find($id);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Ticket
     */
    public function update(int $id, array $data): Ticket
    {
        $ticket = $this->findById($id);

        $ticket->update($data);

        return $ticket;
    }
}
