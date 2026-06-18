<?php

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\Site;
use App\Models\Ticket;
use App\Models\User;
use App\Repositories\TicketUpdateRepository;

test('finds all updates for ticket', function () {
    $repository = new TicketUpdateRepository();

    $company = Company::factory()->create();
    Site::factory()->create();
    User::factory()->create(['role' => UserRole::Engineer]);
    User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    $ticket = Ticket::factory()->create(['company_id' => $company->id]);

    $ticketUpdates = $repository->findAllByTicket($ticket);
    expect($ticketUpdates)->count()->toBeGreaterThan(0);
});

test('creates a ticket update', function () {
    $repository = new TicketUpdateRepository();

    $company = Company::factory()->create();
    Site::factory()->create();
    User::factory()->create(['role' => UserRole::Engineer]);
    $user = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    $ticket = Ticket::factory()->create(['company_id' => $company->id]);

    $ticketUpdates = $repository->findAllByTicket($ticket);
    $count = count($ticketUpdates);

    $data = [
        'ticket_id' => $ticket->id,
        'text' => 'Random string',
    ];
    $repository->create($ticket, $data, $user);

    $ticketUpdates = $repository->findAllByTicket($ticket);
    $countUpdated = count($ticketUpdates);

    expect($countUpdated)->toBe($count + 1);
});
