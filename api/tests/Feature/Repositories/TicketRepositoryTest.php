<?php

use App\Enums\UserRole;
use App\Models\Site;
use App\Models\User;
use App\Models\Company;
use App\Models\Ticket;
use App\Repositories\TicketRepository;
use function PHPUnit\Framework\assertNull;

test('findAll returns all tickets', function () {
    $company = Company::factory()->create();
    Site::factory()->create();
    User::factory()->create(['role' => UserRole::Engineer]);
    User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    Ticket::factory()->count(3)->create(['company_id' => $company->id]);

    $repository = new TicketRepository();
    $tickets = $repository->findAll();
    $this->assertCount(3, $tickets);
});

test('findById returns ticket by id', function () {
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
