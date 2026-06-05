<?php

use App\Enums\UserRole;
use App\Models\Site;
use App\Models\User;
use App\Models\Company;
use App\Models\Ticket;
use App\Repositories\TicketRepository;

test('findAll returns all tickets', function () {
    Company::factory()->create();
    Site::factory()->create();
    User::factory()->create(['role' => UserRole::Engineer]);
    User::factory()->create(['role' => UserRole::Customer]);
    Ticket::factory()->count(3)->create();

    $repository = new TicketRepository();
    $tickets = $repository->findAll();
    $this->assertCount(3, $tickets);
});
