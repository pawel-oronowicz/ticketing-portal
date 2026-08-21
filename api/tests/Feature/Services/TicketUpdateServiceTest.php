<?php

use App\Enums\UserRole;
use App\Events\TicketUpdateCreated;
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

test('creating a ticket update via API dispatches TicketUpdateCreated event', function () {
    Event::fake();

    Company::factory()->create();
    Site::factory()->create();
    $engineer = User::factory()->create(['role' => UserRole::Engineer]);
    $ticket = Ticket::factory()->create();

    $response = $this->actingAs($engineer)->postJson("/api/tickets/{$ticket->id}/updates", [
        'text' => 'Test update',
    ]);

    $response->assertStatus(201);

    Event::assertDispatched(TicketUpdateCreated::class, function ($event) use ($response) {
        return $event->ticketUpdate->id === $response->json('id');
    });
});

test('creating a ticket update via API persists it to the database', function () {
    Company::factory()->create();
    Site::factory()->create();
    $engineer = User::factory()->create(['role' => UserRole::Engineer]);
    $ticket = Ticket::factory()->create();

    $response = $this->actingAs($engineer)->postJson("/api/tickets/{$ticket->id}/updates", [
        'text' => 'Test update',
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('ticket_updates', [
        'ticket_id' => $ticket->id,
        'text' => 'Test update',
    ]);
});
