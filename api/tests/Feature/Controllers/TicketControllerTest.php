<?php

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\Ticket;
use App\Models\User;

test('unauthenticated user cannot view tickets', function () {
    $response = $this->getJson('/api/tickets');

    $response->assertStatus(401);
});

test('engineer and admin can view all tickets', function () {
    Company::factory()->withSites()->create();
    $engineer = User::factory()->create(['role' => UserRole::Engineer]);

    Ticket::factory()->count(3)->create();

    $response = $this->actingAs($engineer)->getJson('/api/tickets');
    $response->assertStatus(200)
        ->assertJsonCount(3);

    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $response = $this->actingAs($admin)->getJson('/api/tickets');
    $response->assertStatus(200)
        ->assertJsonCount(3);
});

test('customer can only view tickets belonging to their company', function () {
    $company1 = Company::factory()->withSites()->create();
    $company2 = Company::factory()->withSites()->create();
    $customer = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company1->id]);
    User::factory()->create(['role' => UserRole::Engineer]);

    Ticket::factory()->count(2)->create(['company_id' => $company1->id]);
    Ticket::factory()->count(1)->create(['company_id' => $company2->id]);

    $response = $this->actingAs($customer)->getJson('/api/tickets');
    $response->assertStatus(200)
        ->assertJsonCount(2);
});

test('engineer and admin can view any single ticket', function () {
    Company::factory()->withSites()->create();
    $engineer = User::factory()->create(['role' => UserRole::Engineer]);

    $ticket = Ticket::factory()->create();

    $response = $this->actingAs($engineer)->getJson("/api/tickets/{$ticket->id}");
    $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'subject',
            'status' => ['value', 'label', 'is_finalised'],
            'priority' => ['value', 'label'],
        ]);

    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $response = $this->actingAs($admin)->getJson("/api/tickets/{$ticket->id}");
    $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'subject',
            'status' => ['value', 'label', 'is_finalised'],
            'priority' => ['value', 'label'],
        ]);
});

test('customer can view a ticket belonging to their company', function () {
    $company = Company::factory()->withSites()->create();
    $customer = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    User::factory()->create(['role' => UserRole::Engineer]);

    $ticket = Ticket::factory()->create(['company_id' => $company->id]);

    $response = $this->actingAs($customer)->getJson("/api/tickets/{$ticket->id}");
    $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'subject',
            'status' => ['value', 'label', 'is_finalised'],
            'priority' => ['value', 'label'],
        ]);
});

test('customer cannot view a ticket belonging to another company', function () {
    $company1 = Company::factory()->withSites()->create();
    $company2 = Company::factory()->withSites()->create();
    $customer = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company1->id]);
    User::factory()->create(['role' => UserRole::Engineer]);

    $ticket = Ticket::factory()->create(['company_id' => $company2->id]);

    $response = $this->actingAs($customer)->getJson("/api/tickets/{$ticket->id}");
    $response->assertStatus(404);
});

test('engineer and admin can update ticket status and priority', function () {
    $engineer = User::factory()->create(['role' => UserRole::Engineer]);
    Company::factory()->withSites()->create();
    $ticket = Ticket::factory()->create(['status' => TicketStatus::New]);

    $response = $this->actingAs($engineer)->putJson("/api/tickets/{$ticket->id}", [
        'status' => TicketStatus::InProgress->value,
        'priority' => TicketPriority::Low->value,
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('tickets', [
        'id' => $ticket->id,
        'status' => TicketStatus::InProgress->value,
        'priority' => TicketPriority::Low->value,
    ]);
});

test('customer can update ticket status but not priority', function () {
    $company = Company::factory()->withSites()->create();
    $customer = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    User::factory()->create(['role' => UserRole::Engineer]);
    $ticket = Ticket::factory()->create([
        'company_id' => $company->id,
        'status' => TicketStatus::New,
        'priority' => TicketPriority::Low->value
    ]);

    $response = $this->actingAs($customer)->putJson("/api/tickets/{$ticket->id}", [
        'status' => TicketStatus::Cancelled->value,
        'priority' => TicketPriority::High->value,
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('tickets', [
        'id' => $ticket->id,
        'status' => TicketStatus::Cancelled->value,
        'priority' => TicketPriority::Low->value,
    ]);
});
