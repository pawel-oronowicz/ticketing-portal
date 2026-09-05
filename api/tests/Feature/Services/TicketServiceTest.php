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
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->ticketRepository = new TicketRepository();
    $this->ticketUpdateRepository = new TicketUpdateRepository();
    $this->ticketUpdateService = new TicketUpdateService($this->ticketUpdateRepository);
    $this->service = new TicketService($this->ticketRepository, $this->ticketUpdateService);
});

test('finds all tickets', function () {
    $tickets = $this->service->findAll();
    expect($tickets)->toHaveCount(0);

    $company = Company::factory()->create();
    Site::factory()->create();
    User::factory()->create(['role' => UserRole::Engineer]);
    User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    Ticket::factory()->count(3)->create(['company_id' => $company->id]);
    $tickets = $this->service->findAll();
    expect($tickets)->toHaveCount(3);
});

test('findAll caches ticket list', function () {
    $company = Company::factory()->create();
    Site::factory()->create();
    User::factory()->create(['role' => UserRole::Engineer]);
    User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    Ticket::factory()->count(3)->create(['company_id' => $company->id]);

    $this->service->findAll();

    expect(Cache::tags(['tickets'])->has('tickets:all'))->toBeTrue();
});

test('findAll does not query the database on cache hit', function () {
    $company = Company::factory()->create();
    Site::factory()->create();
    User::factory()->create(['role' => UserRole::Engineer]);
    User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    Ticket::factory()->count(3)->create(['company_id' => $company->id]);

    $this->service->findAll(); // populates cache

    DB::enableQueryLog();
    $this->service->findAll(); // should hit cache, not DB
    $queries = DB::getQueryLog();
    DB::disableQueryLog();

    expect($queries)->toBeEmpty();
});

test('finds ticket by ID', function () {
    $company = Company::factory()->create();
    Site::factory()->create();
    User::factory()->create(['role' => UserRole::Engineer]);
    User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    Ticket::factory()->create(['company_id' => $company->id]);
    $ticket = Ticket::factory()->create(['company_id' => $company->id]);

    $result = $this->service->findById($ticket->id);
    expect($result->id)->toBe($ticket->id);

    $result = $this->service->findById($ticket->id + 1);
    expect($result)->toBeNull();
});

test('finds tickets for user', function () {
    $company1 = Company::factory()->withSites()->create();
    $engineer = User::factory()->create(['role' => UserRole::Engineer]);
    $customer = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company1->id]);
    Ticket::factory()->count(3)->create(['company_id' => $company1->id]);

    $tickets = $this->service->findForUser($engineer);
    expect($tickets)->toHaveCount(3);
    $tickets = $this->service->findForUser($customer);
    expect($tickets)->toHaveCount(3);

    $company2 = Company::factory()->withSites()->create();
    Ticket::factory()->count(2)->create(['company_id' => $company2->id]);
    $tickets = $this->service->findForUser($engineer);
    expect($tickets)->toHaveCount(5);
    $tickets = $this->service->findForUser($customer);
    expect($tickets)->toHaveCount(3);
});

test('findForUser returns empty collection if user has no company', function () {
    $company = Company::factory()->create();
    Site::factory()->create();
    User::factory()->create(['role' => UserRole::Engineer]);
    $customer = User::factory()->create(['role' => UserRole::Customer, 'company_id' => null]);

    Ticket::factory()->count(3)->create(['company_id' => $company->id]);
    $tickets = $this->service->findForUser($customer);
    expect($tickets)->toHaveCount(0);
});

test('findForUser creates separate cached ticket lists for different customers', function () {
    $company1 = Company::factory()->withSites()->create();
    $company2 = Company::factory()->withSites()->create();

    $customer1 = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company1->id]);
    $customer2 = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company2->id]);

    User::factory()->create(['role' => UserRole::Engineer]);

    Ticket::factory()->create(['company_id' => $company1->id]);
    Ticket::factory()->count(2)->create(['company_id' => $company2->id]);

    $result1 = $this->service->findForUser($customer1);
    $result2 = $this->service->findForUser($customer2);

    expect($result1)->toHaveCount(1)
        ->and($result2)->toHaveCount(2);
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

    $data = [
        'status' => TicketStatus::InProgress,
        'priority' => TicketPriority::High,
        'assigned_user_id' => $engineer->id,
    ];
    $ticket = $this->service->update($ticket->id, $data, $engineer);
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

    $data = [
        'status' => TicketStatus::InProgress,
        'priority' => TicketPriority::High,
        'assigned_user_id' => $engineer->id,
    ];
    $ticket = $this->service->update($ticket->id, $data, $customer);
    expect($ticket->status)->toBe(TicketStatus::InProgress)
        ->and($ticket->priority)->toBe(TicketPriority::Low)
        ->and($ticket->assigned_user_id)->toBeNull();
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

test('creating a ticket dispatches TicketCreated event', function () {
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

test('creating a ticket invalidates the tickets cache', function () {
    $this->service->findAll(); // populates cache

    expect(Cache::tags(['tickets'])->has('tickets:all'))->toBeTrue();

    $company = Company::factory()->create();
    Site::factory()->create();
    User::factory()->create(['role' => UserRole::Engineer]);
    User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    Ticket::factory()->create(['company_id' => $company->id]);

    expect(Cache::tags(['tickets'])->has('tickets:all'))->toBeFalse();
});
