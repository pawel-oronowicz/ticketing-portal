<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Events\TicketCreated;
use App\Models\Ticket;
use App\Models\User;
use App\Repositories\TicketRepository;
use Cache;
use Illuminate\Database\Eloquent\Collection;

class TicketService
{
    public function __construct(
        private readonly TicketRepository    $ticketRepository,
        private readonly TicketUpdateService $ticketUpdateService,
    ) {}

    private array $restrictedFields = ['priority', 'assigned_user_id'];

    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Cache::tags(['tickets'])->remember('tickets:all', now()->addMinutes(5), function () {
            return $this->ticketRepository->findAll();
        });
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
            return Cache::tags(['tickets'])->remember('tickets:all', now()->addMinutes(5), function () {
                return $this->ticketRepository->findAll();
            });
        } else {
            if(!$user->company) {
                return new Collection();
            }
            return Cache::tags(['tickets', "tickets:company:{$user->company_id}"])->remember(
                "tickets:company:{$user->company_id}",
                now()->addMinutes(5),
                function () use ($user) {
                    return $this->ticketRepository->findAllByCompany($user->company);
                }
            );
        }
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
