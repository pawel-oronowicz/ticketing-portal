<?php

use App\Models\Company;
use App\Models\Site;
use App\Repositories\SiteRepository;
use App\Services\SiteService;

test('finds all sites for company', function () {
    $repository = new SiteRepository();
    $service = new SiteService($repository);

    $company1 = Company::factory()->create();
    Site::factory()->count(3)->create();

    $company2 = Company::factory()->create();
    Site::factory()->create([
        'company_id' => $company2->id,
    ]);

    $sites = $service->findAllByCompany($company1);
    expect($sites)->count()->toBe(3);
});
