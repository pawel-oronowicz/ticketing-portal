<?php

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\Ticket;
use App\Models\User;

test('unauthenticated user cannot view ticket updates', function () {
    Company::factory()->withSites()->create();
    User::factory()->create(['role' => UserRole::Engineer]);
    $ticket = Ticket::factory()->create();
    $response = $this->getJson("/api/tickets/{$ticket->id}/updates");

    $response->assertStatus(401);
});

test('engineer and admin user can view all ticket updates', function () {
    Company::factory()->withSites()->create();

    $engineer = User::factory()->create(['role' => UserRole::Engineer]);
    $ticket = Ticket::factory()->create();

    $response = $this->actingAs($engineer)->getJson("/api/tickets/{$ticket->id}/updates");
    $response->assertStatus(200)
        ->assertJsonCount($ticket->updates()->count());

    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $response = $this->actingAs($admin)->getJson("/api/tickets/{$ticket->id}/updates");
    $response->assertStatus(200)
        ->assertJsonCount($ticket->updates()->count());
});

test('customer user can view ticket updates belonging to their company tickets', function () {
    $company = Company::factory()->withSites()->create();

    User::factory()->create(['role' => UserRole::Engineer]);

    $customer = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    $ticket = Ticket::factory()->create(['company_id' => $company->id]);

    $response = $this->actingAs($customer)->getJson("/api/tickets/{$ticket->id}/updates");
    $response->assertStatus(200)
        ->assertJsonCount($ticket->updates()->count());
});

test('customer user cannot view ticket updates belonging to other company tickets', function () {
    $company1 = Company::factory()->withSites()->create();
    $company2 = Company::factory()->withSites()->create();

    User::factory()->create(['role' => UserRole::Engineer]);

    $customer = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company1->id]);
    $ticket = Ticket::factory()->create(['company_id' => $company2->id]);

    $response = $this->actingAs($customer)->getJson("/api/tickets/{$ticket->id}/updates");
    $response->assertStatus(404);
});

test('engineer and admin user can create ticket updates on any ticket', function () {
    Company::factory()->withSites()->create();

    $engineer = User::factory()->create(['role' => UserRole::Engineer]);
    $ticket = Ticket::factory()->create();

    $response = $this->actingAs($engineer)->postJson("/api/tickets/{$ticket->id}/updates", [
        'text' => 'Test update'
    ]);
    $response->assertStatus(201);

    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $response = $this->actingAs($admin)->postJson("/api/tickets/{$ticket->id}/updates", [
        'text' => 'Test update'
    ]);
    $response->assertStatus(201);
});

test('customer user can create ticket updates on tickets belonging to their company', function () {
    $company = Company::factory()->withSites()->create();
    User::factory()->create(['role' => UserRole::Engineer]);

    $customer = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company->id]);
    $ticket = Ticket::factory()->create(['company_id' => $company->id]);

    $response = $this->actingAs($customer)->postJson("/api/tickets/{$ticket->id}/updates", [
        'text' => 'Test update'
    ]);
    $response->assertStatus(201);
});

test('customer user cannot create ticket updates on tickets belonging to another company', function () {
    $company1 = Company::factory()->withSites()->create();
    $company2 = Company::factory()->withSites()->create();
    User::factory()->create(['role' => UserRole::Engineer]);

    $customer = User::factory()->create(['role' => UserRole::Customer, 'company_id' => $company1->id]);
    $ticket = Ticket::factory()->create(['company_id' => $company2->id]);

    $response = $this->actingAs($customer)->postJson("/api/tickets/{$ticket->id}/updates", [
        'text' => 'Test update'
    ]);
    $response->assertStatus(404);
});
