<?php

use App\Models\Company;
use App\Repositories\CompanyRepository;

test('findAll returns all companies', function () {
    Company::factory()->count(3)->create();

    $repository = new CompanyRepository();
    $companies = $repository->findAll();
    $this->assertCount(3, $companies);
});
