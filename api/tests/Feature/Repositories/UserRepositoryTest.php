<?php

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;

test('finds user by email', function () {
    $repository = new UserRepository();
    User::factory()->count(3)->create();

    $email = 'johndoe@example.com';
    User::factory()->create(['email' => $email]);

    $user = $repository->findByEmail($email);
    $this->assertEquals($email, $user->email);

    $user = $repository->findByEmail('randomInvalidEmail@example.com');
    $this->assertNull($user);
});

test('finds all users', function () {
    $repository = new UserRepository();
    User::factory()->count(3)->create();

    $users = $repository->findAll();
    $this->assertCount(3, $users);
});

test('finds users by role', function () {
    $repository = new UserRepository();
    User::factory()->count(3)->create([
        'role' => UserRole::Customer,
    ]);
    User::factory()->count(2)->create([
        'role' => UserRole::Engineer,
    ]);

    $users = $repository->findAll(UserRole::Engineer);
    $this->assertCount(2, $users);
});

test('finds all company users', function () {
    $repository = new UserRepository();
    $company1 = Company::factory()->create();
    $company2 = Company::factory()->create();
    $company3 = Company::factory()->create();

    User::factory()->count(2)->create(['company_id' => $company1->id]);
    User::factory()->count(3)->create(['company_id' => $company2->id]);

    $users = $repository->findAllByCompany($company1);
    $this->assertCount(2, $users);

    $users = $repository->findAllByCompany($company2);
    $this->assertCount(3, $users);

    $users = $repository->findAllByCompany($company3);
    $this->assertCount(0, $users);
});


test('returns null when password is incorrect', function () {
    $repository = new UserRepository();
    $user = User::factory()->make(['password' => Hash::make('correctpassword')]);

    $service = new UserService($repository);
    $result = $service->authenticateUser(['email' => $user->email, 'password' => 'correctpassword']);

    expect($result)->toBeNull();
});

