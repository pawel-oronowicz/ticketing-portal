<?php

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\Site;
use App\Models\Ticket;
use App\Models\TicketUpdate;
use App\Models\User;
use App\Repositories\TicketUpdateRepository;
use App\Services\TicketUpdateService;

test('finds all updates for ticket for internal user', function () {
    $repository = new TicketUpdateRepository();
    $service = new TicketUpdateService($repository);

    $company = Company::factory()->create();
    Site::factory()->create();
    $engineer = User::factory()->create(['role' => UserRole::Engineer]);
    User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    $ticket = Ticket::factory()->create(['company_id' => $company->id]);
    TicketUpdate::factory()->count(2)->create([
        'ticket_id' => $ticket->id,
        'created_by_user_id' => $engineer->id,
    ]);

    $ticketUpdates = $service->findAllByTicket($ticket, $engineer);
    expect($ticketUpdates)->count()->toBe(2);
});

test('finds non-internal updates for ticket for customer user', function () {
    $repository = new TicketUpdateRepository();
    $service = new TicketUpdateService($repository);

    $company = Company::factory()->create();
    Site::factory()->create();
    $engineer = User::factory()->create(['role' => UserRole::Engineer]);
    $customer = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    $ticket = Ticket::factory()->create(['company_id' => $company->id]);
    TicketUpdate::factory()->count(2)->create([
        'ticket_id' => $ticket->id,
        'created_by_user_id' => $engineer->id,
        'is_internal' => false
    ]);
    TicketUpdate::factory()->create([
        'ticket_id' => $ticket->id,
        'created_by_user_id' => $customer->id,
        'is_internal' => true
    ]);

    $ticketUpdates = $service->findAllByTicket($ticket, $customer);
    expect($ticketUpdates)->count()->toBe(2);
});

test('creates a ticket update', function () {
    $repository = new TicketUpdateRepository();
    $service = new TicketUpdateService($repository);

    $company = Company::factory()->create();
    Site::factory()->create();
    User::factory()->create(['role' => UserRole::Engineer]);
    $customer = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    $ticket = Ticket::factory()->create(['company_id' => $company->id]);

    $ticketUpdates = $service->findAllByTicket($ticket, $customer);
    $count = count($ticketUpdates);

    $data = [
        'ticket_id' => $ticket->id,
        'text' => 'Random string',
    ];
    $service->createTicketUpdate($ticket, $data, $customer);

    $ticketUpdates = $service->findAllByTicket($ticket, $customer);
    $countUpdated = count($ticketUpdates);

    expect($countUpdated)->toBe($count + 1);
});
