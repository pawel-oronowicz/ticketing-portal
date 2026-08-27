<?php

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Models\Site;
use App\Models\User;
use App\Models\Company;
use App\Models\Ticket;
use App\Repositories\TicketRepository;
use function PHPUnit\Framework\assertNull;

test('finds all tickets', function () {
    $company = Company::factory()->create();
    Site::factory()->create();
    User::factory()->create(['role' => UserRole::Engineer]);
    User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    Ticket::factory()->count(3)->create(['company_id' => $company->id]);

    $repository = new TicketRepository();
    $tickets = $repository->findAll();
    $this->assertCount(3, $tickets);
});

test('finds ticket by ID', function () {
    $company = Company::factory()->create();
    Site::factory()->create();
    User::factory()->create(['role' => UserRole::Engineer]);
    User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    Ticket::factory()->count(3)->create(['company_id' => $company->id]);

    $repository = new TicketRepository();
    $ticket = $repository->findById(1);
    $this->assertEquals(1, $ticket->id);

    $ticket = $repository->findById(4);
    $this->assertNull($ticket);
});

test('finds tickets by company', function () {
    $repository = new TicketRepository();

    $company = Company::factory()->withSites()->create();
    Site::factory()->create();
    User::factory()->create(['role' => UserRole::Engineer]);
    User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    Ticket::factory()->count(3)->create(['company_id' => $company->id]);

    $tickets = $repository->findAllByCompany($company);
    expect($tickets)->toHaveCount(3);

    $company2 = Company::factory()->withSites()->create();
    Site::factory()->create();
    Ticket::factory()->count(2)->create(['company_id' => $company2->id]);

    $tickets = $repository->findAllByCompany($company2);
    expect($tickets)->toHaveCount(2);
});

test('user updates ticket with data restricted to internal users', function () {
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

    $data = [
        'status' => TicketStatus::InProgress,
        'priority' => TicketPriority::High,
        'assigned_user_id' => $engineer->id,
    ];
    $ticket = $repository->update($ticket->id, $data);
    expect($ticket->status)->toBe(TicketStatus::InProgress)
        ->and($ticket->priority)->toBe(TicketPriority::High)
        ->and($ticket->assigned_user_id)->toBe($engineer->id);
});

test('creates a ticket', function () {
    $repository = new TicketRepository();

    $company = Company::factory()->create();
    $site = Site::factory()->create();
    $engineer = User::factory()->create(['role' => UserRole::Engineer]);
    $user = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);

    $data = [
        'subject' => 'Test subject',
        'description' => 'Test description',
        'company_id' => $company->id,
        'site_id' => $site->id,
        'priority' => TicketPriority::Low,
        'assigned_user_id' => $engineer->id,
    ];
    $repository->create($data, $user);

    $this->assertDatabaseHas('tickets', [
        'subject' => 'Test subject',
        'company_id' => $company->id,
        'site_id' => $site->id,
        'priority' => TicketPriority::Low,
        'assigned_user_id' => $engineer->id,
        'created_by_user_id' => $user->id,
    ]);
});
