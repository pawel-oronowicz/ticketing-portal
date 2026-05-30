<?php

use App\Models\User;
use function Pest\Laravel\actingAs;

test('registered user can log in with valid credentials', function () {
   User::factory()->create(['email' => 'johndoe@example.com', 'password' => bcrypt('password123')]);

   $response = $this->postJson('/api/login', [
      'email' => 'johndoe@example.com',
      'password' => 'password123',
   ]);

   $response->assertStatus(200)
       ->assertJsonStructure([
           'token',
           'user' => ['id', 'name', 'email']
       ]);
});

test('registered user cannot log in with invalid credentials', function () {
    User::factory()->create(['email' => 'johndoe@example.com', 'password' => 'password123']);

    $response = $this->postJson('/api/login', [
        'email' => 'johndoe@example.com',
        'password' => 'password1234',
    ]);

    $response->assertStatus(403);

    $response = $this->postJson('/api/login', [
        'email' => 'johndoe123@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(403);
});

test('login requires email and password', function () {
   $response = $this->postJson('/api/login', []);

   $response->assertStatus(422)
       ->assertJsonValidationErrors(['email', 'password']);
});

test('logged in user cannot access login route', function () {
   $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/login', [
        'email' => 'johndoe@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(403);
});
