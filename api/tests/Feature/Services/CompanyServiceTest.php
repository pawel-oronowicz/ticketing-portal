<?php

use App\Models\Company;
use App\Repositories\CompanyRepository;
use App\Services\CompanyService;

test('finds all companies', function () {
    $repository = new CompanyRepository();
    $service = new CompanyService($repository);

    $companies = $service->findAll();
    $this->assertCount(0, $companies);

    Company::factory()->count(3)->create();
    $companies = $service->findAll();
    $this->assertCount(3, $companies);
});
