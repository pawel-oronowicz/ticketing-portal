<?php

use App\Enums\UserRole;
use App\Models\Ticket;
use App\Models\User;
use App\Policies\TicketPolicy;

test('admin or engineer can view any ticket', function () {
    $admin = User::factory()->make(['role' => UserRole::Admin, 'company_id' => 1]);
    $engineer = User::factory()->make(['role' => UserRole::Engineer, 'company_id' => 1]);
    $ticket1 = new Ticket(['company_id' => 1]);
    $ticket2 = new Ticket(['company_id' => 2]);

    $policy = new TicketPolicy();

    expect($policy->view($admin, $ticket1))->toBeTrue()
        ->and($policy->view($admin, $ticket2))->toBeTrue()
        ->and($policy->view($engineer, $ticket1))->toBeTrue()
        ->and($policy->view($engineer, $ticket2))->toBeTrue();
});

test('customer can only view their company tickets', function () {
    $customer = User::factory()->make(['role' => UserRole::Customer, 'company_id' => 1]);
    $ticket1 = new Ticket(['company_id' => 1]);
    $ticket2 = new Ticket(['company_id' => 2]);

    $policy = new TicketPolicy();

    expect($policy->view($customer, $ticket1))->toBeTrue()
        ->and($policy->view($customer, $ticket2))->toBeFalse();
});

test('any user can create a ticket', function () {
    $admin = User::factory()->make(['role' => UserRole::Admin, 'company_id' => 1]);
    $engineer = User::factory()->make(['role' => UserRole::Engineer, 'company_id' => 1]);
    $customer = User::factory()->make(['role' => UserRole::Customer, 'company_id' => 1]);

    $policy = new TicketPolicy();

    expect($policy->create($admin))->toBeTrue()
        ->and($policy->create($engineer))->toBeTrue()
        ->and($policy->create($customer))->toBeTrue();
});

test('admin or engineer can update any ticket', function () {
    $admin = User::factory()->make(['role' => UserRole::Admin, 'company_id' => 1]);
    $engineer = User::factory()->make(['role' => UserRole::Engineer, 'company_id' => 1]);
    $ticket1 = new Ticket(['company_id' => 1]);
    $ticket2 = new Ticket(['company_id' => 2]);

    $policy = new TicketPolicy();

    expect($policy->update($admin, $ticket1))->toBeTrue()
        ->and($policy->update($admin, $ticket2))->toBeTrue()
        ->and($policy->update($engineer, $ticket1))->toBeTrue()
        ->and($policy->update($engineer, $ticket2))->toBeTrue();
});

test('customer can only update their company tickets', function () {
    $customer = User::factory()->make(['role' => UserRole::Customer, 'company_id' => 1]);
    $ticket1 = new Ticket(['company_id' => 1]);
    $ticket2 = new Ticket(['company_id' => 2]);

    $policy = new TicketPolicy();

    expect($policy->update($customer, $ticket1))->toBeTrue()
        ->and($policy->update($customer, $ticket2))->toBeFalse();
});

test('only admin can delete or restore a ticket', function () {
    $admin = User::factory()->make(['role' => UserRole::Admin, 'company_id' => 1]);
    $engineer = User::factory()->make(['role' => UserRole::Engineer, 'company_id' => 1]);
    $customer = User::factory()->make(['role' => UserRole::Customer, 'company_id' => 1]);
    $ticket = new Ticket(['company_id' => 1]);

    $policy = new TicketPolicy();

    expect($policy->delete($admin, $ticket))->toBeTrue()
        ->and($policy->delete($engineer, $ticket))->toBeFalse()
        ->and($policy->delete($customer, $ticket))->toBeFalse()
        ->and($policy->restore($admin, $ticket))->toBeTrue()
        ->and($policy->restore($engineer, $ticket))->toBeFalse()
        ->and($policy->restore($customer, $ticket))->toBeFalse();
});

test('nobody can force delete a ticket', function () {
    $admin = User::factory()->make(['role' => UserRole::Admin, 'company_id' => 1]);
    $engineer = User::factory()->make(['role' => UserRole::Engineer, 'company_id' => 1]);
    $customer = User::factory()->make(['role' => UserRole::Customer, 'company_id' => 1]);
    $ticket = new Ticket(['company_id' => 1]);

    $policy = new TicketPolicy();

    expect($policy->forceDelete($admin, $ticket))->toBeFalse()
        ->and($policy->forceDelete($engineer, $ticket))->toBeFalse()
        ->and($policy->forceDelete($customer, $ticket))->toBeFalse();
});
