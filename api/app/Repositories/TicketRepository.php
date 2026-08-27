<?php

namespace App\Repositories;

use App\Enums\TicketStatus;
use App\Models\Company;
use App\Models\Ticket;
use App\Models\User;
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
     * @param Company $company
     * @return Collection
     */
    public function findAllByCompany(Company $company): Collection
    {
        return Ticket::with(['createdBy', 'assigned', 'company', 'site'])
            ->where('company_id', $company->id)
            ->get();
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

    /**
     * @param array $data
     * @param User $user
     * @return Ticket
     */
    public function create(array $data, User $user): Ticket
    {
        $ticket = new Ticket();
        $ticket->fill($data);
        $ticket->created_by_user_id = $user->id;
        $ticket->status = TicketStatus::New;
        $ticket->save();

        return $ticket;
    }
}
