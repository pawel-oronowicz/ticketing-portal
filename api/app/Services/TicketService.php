<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Events\TicketCreated;
use App\Models\Ticket;
use App\Models\User;
use App\Repositories\TicketRepository;
use Illuminate\Database\Eloquent\Collection;

class TicketService
{
    public function __construct(
        private TicketRepository $ticketRepository,
        private TicketUpdateService $ticketUpdateService,
    ) {}

    private array $restrictedFields = ['priority', 'assigned_user_id'];

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

    /**
     * @param User $user
     * @return Collection
     */
    public function findForUser(User $user): Collection
    {
        if($user->role->isInternal()) {
            $tickets = $this->ticketRepository->findAll();
        } else {
            if(!$user->company) {
                return new Collection();
            }
            $tickets = $this->ticketRepository->findAllByCompany($user->company);
        }

        return $tickets;
    }

    /**
     * @param int $id
     * @param array $data
     * @param User $user
     * @return Ticket
     */
    public function update(int $id, array $data, User $user): Ticket
    {
        if(!in_array($user->role, [UserRole::Admin, UserRole::Engineer])) {
            foreach ($this->restrictedFields as $field) {
                unset($data[$field]);
            }
        }

        return $this->ticketRepository->update($id, $data);
    }

    /**
     * @param array $data
     * @param User $user
     * @return Ticket
     */
    public function create(array $data, User $user): Ticket
    {
        $ticket = $this->ticketRepository->create($data, $user);
        $ticketUpdateData = [
            'ticket_id' => $ticket->id,
            'text' => $data['description'],
            'is_internal' => false,
            'created_by_user_id' => auth()->id(),
        ];

        TicketCreated::dispatch($ticket);

        $this->ticketUpdateService->create($ticket, $ticketUpdateData, $user);

        return $ticket;
    }
}
