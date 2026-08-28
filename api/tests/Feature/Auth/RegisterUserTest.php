<?php

use App\Models\User;

test('user can register with valid credentials', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response
        ->assertStatus(201)
        ->assertJsonStructure([
            'token',
            'user' => ['id', 'name', 'email'],
        ]);

    $this->assertDatabaseHas('users', ['email' => 'johndoe@example.com']);
});

test('registered user password is hashed in the database', function () {
    $this->postJson('/api/register', [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $user = User::where('email', 'johndoe@example.com')->first();
    expect($user->password)->not->toBe('password123');
});

test('user cannot register with missing credentials', function () {
    $response = $this->postJson('/api/register', [
        'email' => 'johndoe@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name']);

    $response = $this->postJson('/api/register', [
        'name' => 'John Doe',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);

    $response = $this->postJson('/api/register', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'password']);
});

test('user cannot register with mismatched passwords', function () {
    $response = $this->postJson('/api/register', [
        'email' => 'johndoe@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password1234',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

test('user cannot register if email already exists', function () {
    User::factory()->create(['email' => 'johndoe@example.com']);

    $response = $this->postJson('/api/register', [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('logged in user cannot access register route', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/register', [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(403);
});
