<?php

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
    $this->assertCount(0, $tickets);

    $company = Company::factory()->create();
    Site::factory()->create();
    User::factory()->create(['role' => UserRole::Engineer]);
    User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    Ticket::factory()->count(3)->create(['company_id' => $company->id]);
    $tickets = $service->findAll();
    $this->assertCount(3, $tickets);
});

test('finds ticket by ID', function () {
    $company = Company::factory()->create();
    Site::factory()->create();
    User::factory()->create(['role' => UserRole::Engineer]);
    User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    Ticket::factory()->count(3)->create(['company_id' => $company->id]);

    $repository = new TicketRepository();
    $service = new TicketService($repository);
    $ticket = $service->findById(2);
    $this->assertEquals(2, $ticket->id);

    $ticket = $service->findById(123);
    $this->assertNull($ticket);
});
