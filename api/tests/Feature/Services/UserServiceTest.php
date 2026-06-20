<?php

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;

test('returns null when email not found', function () {
    $repository = new UserRepository();
    $service = new UserService($repository);

    $result = $service->authenticateUser(['email' => 'johndoe123@example.com', 'password' => 'password']);
    expect($result)->toBeNull();
});

test('finds users by company', function () {
    $company1 = Company::factory()->create();
    $company2 = Company::factory()->create();
    $company3 = Company::factory()->create();

    User::factory()->count(2)->create(['company_id' => $company1->id]);
    User::factory()->count(3)->create(['company_id' => $company2->id]);

    $repository = new UserRepository();
    $service = new UserService($repository);

    $companyUsers = $service->findAllByCompany($company1);
    expect($companyUsers)->toHaveCount(2);

    $companyUsers = $service->findAllByCompany($company3);
    expect($companyUsers)->toHaveCount(0);
});

test('finds users by role', function () {
    $repository = new UserRepository();
    $service = new UserService($repository);
    User::factory()->count(3)->create([
        'role' => UserRole::Customer,
    ]);
    User::factory()->count(2)->create([
        'role' => UserRole::Engineer,
    ]);

    $users = $service->findAll(UserRole::Engineer->value);
    $this->assertCount(2, $users);
});
