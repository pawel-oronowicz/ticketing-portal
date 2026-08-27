<?php

use App\Models\Company;
use App\Models\Site;
use App\Repositories\SiteRepository;

test('finds sites by company', function () {
    $repository = new SiteRepository();

    $company1 = Company::factory()->create();
    Site::factory()->count(3)->create(['company_id' => $company1->id]);

    $sites = $repository->findAllByCompany($company1);
    expect($sites)->toHaveCount(3);

    $company2 = Company::factory()->create();
    Site::factory()->count(2)->create(['company_id' => $company2->id]);

    $sites = $repository->findAllByCompany($company2);
    expect($sites)->toHaveCount(2);
});
