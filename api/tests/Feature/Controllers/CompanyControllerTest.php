<?php

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\User;

test('unauthenticated user cannot view companies', function () {
    $response = $this->getJson('/api/companies');

    $response->assertStatus(401);
});

test('engineer and admin can view the companies list', function () {
    Company::factory()->count(3)->create();

    $engineer = User::factory()->create(['role' => UserRole::Engineer]);
    $response = $this->actingAs($engineer)->getJson('/api/companies');
    $response->assertStatus(200)
        ->assertJsonCount(3);

    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $response = $this->actingAs($admin)->getJson('/api/companies');
    $response->assertStatus(200)
        ->assertJsonCount(3);
});

test('customer cannot view the companies list', function () {
    Company::factory()->count(3)->create();

    $customer = User::factory()->create(['role' => UserRole::Customer]);
    $response = $this->actingAs($customer)->getJson('/api/companies');
    $response->assertStatus(404);
});
