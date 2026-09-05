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

beforeEach(function () {
    $this->repository = new TicketRepository();
    $this->company = Company::factory()->create();
    $this->site = Site::factory()->create();
    $this->engineer = User::factory()->create(['role' => UserRole::Engineer]);
    $this->user = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $this->company->id]);
});

test('finds all tickets', function () {
    Ticket::factory()->count(3)->create(['company_id' => $this->company->id]);

    $repository = new TicketRepository();
    $tickets = $repository->findAll();
    $this->assertCount(3, $tickets);
});

test('finds ticket by ID', function () {
    $ticket = Ticket::factory()->create(['company_id' => $this->company->id]);

    $result = $this->repository->findById($ticket->id);
    $this->assertEquals($result->id, $ticket->id);

    $result = $this->repository->findById($ticket->id + 1);
    $this->assertNull($result);
});

test('finds tickets by company', function () {
    Ticket::factory()->count(3)->create(['company_id' => $this->company->id]);

    $tickets = $this->repository->findAllByCompany($this->company);
    expect($tickets)->toHaveCount(3);

    $company2 = Company::factory()->withSites()->create();
    Site::factory()->create();
    Ticket::factory()->count(2)->create(['company_id' => $company2->id]);

    $tickets = $this->repository->findAllByCompany($company2);
    expect($tickets)->toHaveCount(2);
});

test('user updates ticket with data restricted to internal users', function () {
    $ticket = Ticket::factory()->create([
        'created_by_user_id' => $this->engineer->id,
        'status' => TicketStatus::New,
        'priority' => TicketPriority::Low,
        'assigned_user_id' => null
    ]);

    $repository = new TicketRepository();

    $data = [
        'status' => TicketStatus::InProgress,
        'priority' => TicketPriority::High,
        'assigned_user_id' => $this->engineer->id,
    ];
    $ticket = $repository->update($ticket->id, $data);
    expect($ticket->status)->toBe(TicketStatus::InProgress)
        ->and($ticket->priority)->toBe(TicketPriority::High)
        ->and($ticket->assigned_user_id)->toBe($this->engineer->id);
});

test('creates a ticket', function () {
    $data = [
        'subject' => 'Test subject',
        'description' => 'Test description',
        'company_id' => $this->company->id,
        'site_id' => $this->site->id,
        'priority' => TicketPriority::Low,
        'assigned_user_id' => $this->engineer->id,
    ];
    $this->repository->create($data, $this->user);

    $this->assertDatabaseHas('tickets', [
        'subject' => 'Test subject',
        'company_id' => $this->company->id,
        'site_id' => $this->site->id,
        'priority' => TicketPriority::Low,
        'assigned_user_id' => $this->engineer->id,
        'created_by_user_id' => $this->user->id,
    ]);
});
