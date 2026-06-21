<?php

use App\Enums\UserRole;
use App\Models\User;

test('unauthenticated user cannot fetch enums', function () {
    $response = $this->getJson('/api/enums');

    $response->assertStatus(401);
});

test('index returns 2 enum parents', function () {
    $customer = User::factory()->create(['role' => UserRole::Customer]);

    $response = $this->actingAs($customer)->getJson("/api/enums");
    $response->assertStatus(200)
        ->assertJsonCount(2);
});
