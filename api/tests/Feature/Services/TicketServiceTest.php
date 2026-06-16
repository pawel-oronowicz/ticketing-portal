<?php

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\Site;
use App\Models\Ticket;
use App\Models\User;
use App\Repositories\TicketRepository;
use App\Services\TicketService;

test('finds all tickets', function () {
    $repository = new TicketRepository();
    $service = new TicketService($repository);

    $tickets = $service->findAll();
    expect($tickets)->toHaveCount(0);

    $company = Company::factory()->create();
    Site::factory()->create();
    User::factory()->create(['role' => UserRole::Engineer]);
    User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    Ticket::factory()->count(3)->create(['company_id' => $company->id]);
    $tickets = $service->findAll();
    expect($tickets)->toHaveCount(3);
});

test('finds ticket by ID', function () {
    $repository = new TicketRepository();
    $service = new TicketService($repository);

    $company = Company::factory()->create();
    Site::factory()->create();
    User::factory()->create(['role' => UserRole::Engineer]);
    User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    Ticket::factory()->count(3)->create(['company_id' => $company->id]);

    $ticket = $service->findById(2);
    expect($ticket->id)->toBe(2);

    $ticket = $service->findById(123);
    expect($ticket)->toBeNull();
});

test('finds tickets for user', function () {
    $repository = new TicketRepository();
    $service = new TicketService($repository);

    $company1 = Company::factory()->create();
    Site::factory()->create();
    $engineer = User::factory()->create(['role' => UserRole::Engineer]);
    $customer = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company1->id]);
    Ticket::factory()->count(3)->create(['company_id' => $company1->id]);

    $tickets = $service->findForUser($engineer);
    expect($tickets)->toHaveCount(3);
    $tickets = $service->findForUser($customer);
    expect($tickets)->toHaveCount(3);

    $company2 = Company::factory()->create();
    Ticket::factory()->count(2)->create(['company_id' => $company2->id]);
    $tickets = $service->findForUser($engineer);
    expect($tickets)->toHaveCount(5);
    $tickets = $service->findForUser($customer);
    expect($tickets)->toHaveCount(3);
});

test('engineer updates ticket with data restricted to internal users', function () {
    $company = Company::factory()->create();
    Site::factory()->create();
    $engineer = User::factory()->create(['role' => UserRole::Engineer]);
    User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    $ticket = Ticket::factory()->create([
        'created_by_user_id' => $engineer->id,
        'status' => TicketStatus::New,
        'priority' => TicketPriority::Low,
        'assigned_user_id' => null
    ]);

    $repository = new TicketRepository();
    $service = new TicketService($repository);

    $data = [
        'status' => TicketStatus::InProgress,
        'priority' => TicketPriority::High,
        'assigned_user_id' => $engineer->id,
    ];
    $ticket = $service->update($ticket->id, $data, $engineer);
    expect($ticket->status)->toBe(TicketStatus::InProgress)
        ->and($ticket->priority)->toBe(TicketPriority::High)
        ->and($ticket->assigned_user_id)->toBe($engineer->id);
});

test('customer cannot update ticket with data restricted to internal users', function () {
    $company = Company::factory()->create();
    Site::factory()->create();
    $engineer = User::factory()->create(['role' => UserRole::Engineer]);
    $customer = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    $ticket = Ticket::factory()->create([
        'created_by_user_id' => $engineer->id,
        'status' => TicketStatus::New,
        'priority' => TicketPriority::Low,
        'assigned_user_id' => null
    ]);

    $repository = new TicketRepository();
    $service = new TicketService($repository);

    $data = [
        'status' => TicketStatus::InProgress,
        'priority' => TicketPriority::High,
        'assigned_user_id' => $engineer->id,
    ];
    $ticket = $service->update($ticket->id, $data, $customer);
    expect($ticket->status)->toBe(TicketStatus::New)
        ->and($ticket->priority)->toBe(TicketPriority::Low)
        ->and($ticket->assigned_user_id)->toBe($engineer->null);
});
