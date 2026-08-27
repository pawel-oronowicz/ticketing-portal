<?php

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Events\TicketCreated;
use App\Models\Company;
use App\Models\Site;
use App\Models\Ticket;
use App\Models\User;
use App\Repositories\TicketRepository;
use App\Repositories\TicketUpdateRepository;
use App\Services\TicketService;
use App\Services\TicketUpdateService;

test('finds all tickets', function () {
    $ticketRepository = new TicketRepository();
    $ticketUpdateRepository = new TicketUpdateRepository();
    $ticketUpdateService = new TicketUpdateService($ticketUpdateRepository);
    $service = new TicketService($ticketRepository, $ticketUpdateService);

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
    $ticketRepository = new TicketRepository();
    $ticketUpdateRepository = new TicketUpdateRepository();
    $ticketUpdateService = new TicketUpdateService($ticketUpdateRepository);
    $service = new TicketService($ticketRepository, $ticketUpdateService);

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
    $ticketRepository = new TicketRepository();
    $ticketUpdateRepository = new TicketUpdateRepository();
    $ticketUpdateService = new TicketUpdateService($ticketUpdateRepository);
    $service = new TicketService($ticketRepository, $ticketUpdateService);

    $company1 = Company::factory()->withSites()->create();
    $engineer = User::factory()->create(['role' => UserRole::Engineer]);
    $customer = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company1->id]);
    Ticket::factory()->count(3)->create(['company_id' => $company1->id]);

    $tickets = $service->findForUser($engineer);
    expect($tickets)->toHaveCount(3);
    $tickets = $service->findForUser($customer);
    expect($tickets)->toHaveCount(3);

    $company2 = Company::factory()->withSites()->create();
    Ticket::factory()->count(2)->create(['company_id' => $company2->id]);
    $tickets = $service->findForUser($engineer);
    expect($tickets)->toHaveCount(5);
    $tickets = $service->findForUser($customer);
    expect($tickets)->toHaveCount(3);
});

test('findsForUser returns empty collection if user has no company', function () {
    $ticketRepository = new TicketRepository();
    $ticketUpdateRepository = new TicketUpdateRepository();
    $ticketUpdateService = new TicketUpdateService($ticketUpdateRepository);
    $service = new TicketService($ticketRepository, $ticketUpdateService);

    $company = Company::factory()->create();
    Site::factory()->create();
    User::factory()->create(['role' => UserRole::Engineer]);
    $customer = User::factory()->create(['role' => UserRole::Customer, 'company_id' => null]);

    Ticket::factory()->count(3)->create(['company_id' => $company->id]);
    $tickets = $service->findForUser($customer);
    expect($tickets)->toHaveCount(0);
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

    $ticketRepository = new TicketRepository();
    $ticketUpdateRepository = new TicketUpdateRepository();
    $ticketUpdateService = new TicketUpdateService($ticketUpdateRepository);
    $service = new TicketService($ticketRepository, $ticketUpdateService);

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

    $ticketRepository = new TicketRepository();
    $ticketUpdateRepository = new TicketUpdateRepository();
    $ticketUpdateService = new TicketUpdateService($ticketUpdateRepository);
    $service = new TicketService($ticketRepository, $ticketUpdateService);

    $data = [
        'status' => TicketStatus::InProgress,
        'priority' => TicketPriority::High,
        'assigned_user_id' => $engineer->id,
    ];
    $ticket = $service->update($ticket->id, $data, $customer);
    expect($ticket->status)->toBe(TicketStatus::InProgress)
        ->and($ticket->priority)->toBe(TicketPriority::Low)
        ->and($ticket->assigned_user_id)->toBe($engineer->null);
});

test('creating a ticket persists it to the database', function () {
    $company = Company::factory()->create();
    $site = Site::factory()->create();
    $engineer = User::factory()->create(['role' => UserRole::Engineer]);

    $response = $this->actingAs($engineer)->postJson("/api/tickets", [
        'subject' => 'Test subject',
        'description' => 'Test description',
        'company_id' => $company->id,
        'site_id' => $site->id,
        'priority' => TicketPriority::Low,
        'assigned_user_id' => $engineer->id,
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('tickets', [
        'subject' => 'Test subject',
        'company_id' => $company->id,
        'site_id' => $site->id,
        'priority' => TicketPriority::Low,
        'assigned_user_id' => $engineer->id,
        'created_by_user_id' => $engineer->id,
    ]);
});

test('creating a ticket dispatches TicketUpdateCreated event', function () {
    Event::fake();

    $company = Company::factory()->create();
    $site = Site::factory()->create();
    $engineer = User::factory()->create(['role' => UserRole::Engineer]);

    $response = $this->actingAs($engineer)->postJson("/api/tickets", [
        'subject' => 'Test subject',
        'description' => 'Test description',
        'company_id' => $company->id,
        'site_id' => $site->id,
        'priority' => TicketPriority::Low,
        'assigned_user_id' => $engineer->id,
    ]);

    $response->assertStatus(201);

    Event::assertDispatched(TicketCreated::class, function ($event) use ($response) {
        return $event->ticket->id === $response->json('id');
    });
});
