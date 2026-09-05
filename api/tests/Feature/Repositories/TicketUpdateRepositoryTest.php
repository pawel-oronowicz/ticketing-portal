<?php

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\Site;
use App\Models\Ticket;
use App\Models\TicketUpdate;
use App\Models\User;
use App\Repositories\TicketUpdateRepository;

beforeEach(function () {
    $this->repository = new TicketUpdateRepository();
    $this->company = Company::factory()->create();
    Site::factory()->create();
    $this->engineer = User::factory()->create(['role' => UserRole::Engineer]);
    $this->customer = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $this->company->id]);
    $this->ticket = Ticket::factory()->create(['company_id' => $this->company->id]);
});

test('finds all updates for ticket for internal user', function () {
    TicketUpdate::factory()->count(2)->create([
        'ticket_id' => $this->ticket->id,
        'created_by_user_id' => $this->engineer->id,
    ]);

    $ticketUpdates = $this->repository->findAllByTicket($this->ticket);
    expect($ticketUpdates)->count()->toBe(2);
});

test('finds non-internal updates for ticket for customer user', function () {
    TicketUpdate::factory()->count(2)->create([
        'ticket_id' => $this->ticket->id,
        'created_by_user_id' => $this->engineer->id,
        'is_internal' => false
    ]);
    TicketUpdate::factory()->create([
        'ticket_id' => $this->ticket->id,
        'created_by_user_id' => $this->customer->id,
        'is_internal' => true
    ]);

    $ticketUpdates = $this->repository->findAllByTicket($this->ticket, true);
    expect($ticketUpdates)->count()->toBe(2);
});

test('creates a ticket update', function () {
    $ticketUpdates = $this->repository->findAllByTicket($this->ticket);
    $count = count($ticketUpdates);

    $data = [
        'ticket_id' => $this->ticket->id,
        'text' => 'Random string',
    ];
    $this->repository->create($this->ticket, $data, $this->customer);

    $ticketUpdates = $this->repository->findAllByTicket($this->ticket);
    $countUpdated = count($ticketUpdates);

    expect($countUpdated)->toBe($count + 1);
});
