<?php

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
